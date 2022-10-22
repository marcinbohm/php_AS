<?php require_once dirname(__FILE__) .'/../config.php';?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
<meta charset="utf-8" />
<title>Kalkulator kredytowy</title>
</head>
<body>

<form action="<?php print(_APP_URL);?>/app/calc.php" method="post">
	<label for="id_kwotaKredytu">Kwota kredytu: </label>
	<input id="id_kwotaKredytu" type="text" name="kwotaKredytu" value="<?php print($kwotaKredytu); ?>" /><br />
    <label for="id_oprocentowanie">Oprocentowanie w skali roku (wartość w procentach): </label>
    <input id="id_oprocentowanie" type="text" name="oprocentowanie" value="<?php print($oprocentowanie); ?>" /><br />
    <label for="id_okres">Okres kredytowania: </label>
    <input id="id_okres" type="text" name="okres" value="<?php print($okres); ?>" /><br />
	<label for="id_raty">Ilość rat w roku: </label>
    <input id="id_raty" type="text" name="raty" value="<?php print($raty); ?>" /><br />
    <label for="id_prowizja">Prowizja (wartość w procentach): </label>
    <input id="id_prowizja" type="text" name="prowizja" value="<?php print($prowizja); ?>" /><br />
	<input type="submit" value="Oblicz" />
</form>	

<?php
//wyświeltenie listy błędów, jeśli istnieją
if (isset($messages)) {
	if (count ( $messages ) > 0) {
		echo '<ol style="margin: 20px; padding: 10px 10px 10px 30px; border-radius: 5px; background-color: #f88; width:300px;">';
		foreach ( $messages as $key => $msg ) {
			echo '<li>'.$msg.'</li>';
		}
		echo '</ol>';
	}
}
?>

<?php if (isset($result)){ ?>
<div style="margin: 20px; padding: 10px; border-radius: 5px; background-color: #ff0; width:300px;">
<?php echo 'Kwota raty miesiecznej: '.$kwotaRaty; ?>
<?php echo 'Koszt kredytu: '.$kosztKredytu; ?>
</div>
<?php } ?>

</body>
</html>