package com.mb_medical_clinic_be.graphql.user;

import com.coxautodev.graphql.tools.GraphQLMutationResolver;
import com.mb_medical_clinic_be.OperationStatus;
import com.mb_medical_clinic_be.dict.DictOperationName;
import com.mb_medical_clinic_be.entity.User;
import com.mb_medical_clinic_be.mapper.SmartMapper;
import com.mb_medical_clinic_be.repository.UserRepository;
import com.mb_medical_clinic_be.security.PasswordEncoderImpl;
import org.springframework.security.access.prepost.PreAuthorize;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import javax.persistence.EntityNotFoundException;
import javax.validation.constraints.NotNull;
import java.util.Optional;

@Service
public class UserMutationResolver implements GraphQLMutationResolver {

    private final UserRepository userRepository;

    private final PasswordEncoderImpl passwordEncoder;

    public UserMutationResolver(UserRepository userRepository, PasswordEncoderImpl passwordEncoder) {
        this.userRepository = userRepository;
        this.passwordEncoder = passwordEncoder;
    }

    @Transactional
    public OperationStatus resetPassword(Integer userId, String oldPassword, String newPassword) {

        OperationStatus operationStatus = new OperationStatus()
                .setOperationName(DictOperationName.UPDATE.getCode())
                .setTargetClassName(User.class.getSimpleName())
                .setSuccess(false)
                .setRecordId(userId);

        Optional<User> user = userRepository.findByUserId(userId);
        if (user.isPresent()) {
            if (passwordEncoder.matches(oldPassword, user.get().getPassword())) {
                user.get().setPassword(passwordEncoder.encode(newPassword));
            } else
                return operationStatus.addMessage("Podane hasło nie jest aktualnym zapisanym hasłem!");
        } else
            return operationStatus.addMessage("Nie znaleziono użytkowanika o podanym Id: " + userId);

        return operationStatus;
    }

    @Transactional
    @PreAuthorize("hasAuthority('USER_ADD_PRIVILEGE')")
    public OperationStatus addUser(UserInput userInput) {
        return saveUser(null, userInput);
    }

    @Transactional
    @PreAuthorize("hasAuthority('USER_MODIFY_PRIVILEGE')")
    public OperationStatus updateUser(@NotNull Integer userInputId, UserInput userInput) {
        return saveUser(userInputId, userInput);
    }

    @Transactional
    @PreAuthorize("hasAuthority('USER_DELETE_PRIVILEGE')")
    public OperationStatus deleteUser(@NotNull Integer userId) {
        User user = userRepository.findById(userId).orElseThrow(EntityNotFoundException::new);

        OperationStatus opStatus = new OperationStatus(User.class.getSimpleName(), DictOperationName.DELETE.getCode());
        opStatus.setRecordId(userId);

        userRepository.delete(user);

        return opStatus.setSuccess(!userRepository.existsById(userId));
    }

    protected OperationStatus saveUser(Integer userId, UserInput userInput) {
        boolean adding = (userId == null);
        User user = (adding ? new User() : userRepository.findById(userId).orElseThrow(EntityNotFoundException::new));

        OperationStatus opStatus = new OperationStatus(User.class.getSimpleName(), adding ? DictOperationName.ADD.getCode() : DictOperationName.UPDATE.getCode());

        SmartMapper.transferData(userInput, user);

        User userSaved = userRepository.save(user);
        Integer id = userSaved.getUserId();
        opStatus.setRecordId(id).setSuccess(id != null);
        return opStatus;
    }
}
