package com.mb_medical_clinic_be.resource.user.registration;

import com.mb_medical_clinic_be.VerificationStatus;
import com.mb_medical_clinic_be.resource.user.registration.model.RegistrationDTO;

public interface RegistrationVerificationService {
    VerificationStatus verifyUserRegistration(RegistrationDTO registrationDTO);
}
