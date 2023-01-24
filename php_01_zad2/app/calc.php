<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';
include _ROOT_PATH.'/app/security/check.php';


// Jeśli działa to pobierze, a jak nie działa to nie pobierze
function getParams(&$x,&$y,&$r){
	$x = isset($_REQUEST['x']) ? $_REQUEST['x'] : null;
	$y = isset($_REQUEST['y']) ? $_REQUEST['y'] : null;
	$r = isset($_REQUEST['r']) ? $_REQUEST['r'] : null;
}
// jesli działa to zwaliduje parametry z przygotowaniem zmiennych do widoku, a jak nie działa to nie
function validate(&$x,&$y,&$r,&$messages){

	if (!(isset($x) && isset($y) && isset($r))) {
		return false;
	}
if ( $x == "") {
	$messages [] = 'Nie podano kwoty';
}
if ( $y == "") {
	$messages [] = 'Nie podano ilości lat';
}
if ( $r == "") {
	$messages [] = 'Nie podano oprocentowania';
}
	if (count ( $messages ) != 0) return false;
if (! is_numeric( $x )) {
		$messages [] = 'Pierwsza wartość nie jest liczbą całkowitą';
	}
	
if (! is_numeric( $y )) {
		$messages [] = 'Druga wartość nie jest liczbą całkowitą';
	}	
if (! is_numeric( $r )) {
		$messages [] = 'trzecia wartość nie jest liczbą całkowitą';
	}
	

	if (count ( $messages ) != 0) return false;
	else return true;
	

	
}

// 3. wykonaj zadanie jeśli wszystko w porządku


function process(&$x,&$y,&$r,&$result){
	

	$x = intval($x);
	$y = intval($y);
	$r = intval($r);

	$result = ($x * (($r/100)/12) * ((1+(($r/100)/12))**($y*12)))/((((1+($r/12/100))**($y*12)))-1);

}
$x = null;
$y = null;
$r = null;
$result = null;
$messages = array();
getParams($x,$y,$r);
if ( validate($x,$y,$r,$messages) ) { // gdy brak błędów
	process($x,$y,$r,$result);
}

include 'calc_view.php';