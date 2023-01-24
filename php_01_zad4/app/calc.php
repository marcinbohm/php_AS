<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';
require_once _ROOT_PATH.'/lib/smarty/Smarty.class.php';


// Jeśli działa to pobierze, a jak nie działa to nie pobierze
function getParams(&$form){
	$form['x'] = isset($_REQUEST['x']) ? $_REQUEST['x'] : null;
	$form['y'] = isset($_REQUEST['y']) ? $_REQUEST['y'] : null;
	$form['r'] = isset($_REQUEST['r']) ? $_REQUEST['r'] : null;
}
// jesli działa to zwaliduje parametry z przygotowaniem zmiennych do widoku, a jak nie działa to nie
function validate(&$form,&$infos,&$messages,&$hide_intro){

	if (!(isset($form['x']) && isset($form['y']) && isset($form['r']))) return false;
		
	
	
	$hide_intro = true;

	$infos [] = 'Przekazano parametry.';
if ( $form['x'] == "") {
	$messages [] = 'Nie podano kwoty';
}
if ( $form['y'] == "") {
	$messages [] = 'Nie podano ilości lat';
}
if ( $form['r'] == "") {
	$messages [] = 'Nie podano oprocentowania';
}
	if (count ( $messages ) != 0) return false;
if (! is_numeric( $form['x'] )) {
		$messages [] = 'Pierwsza wartość nie jest liczbą całkowitą';
	}
	
if (! is_numeric( $form['y'])) {
		$messages [] = 'Druga wartość nie jest liczbą całkowitą';
	}	
if (! is_numeric( $form['r'])) {
		$messages [] = 'Trzecia wartość nie jest liczbą całkowitą';
	}
	

	if (count ( $messages ) != 0) return false;
	else return true;
	

	
}

// 3. wykonaj zadanie jeśli wszystko w porządku


function process(&$form,&$infos,&$messages,&$result){
	$infos [] = 'Parametry poprawne. Wykonuję obliczenia.';

	$form['x'] = floatval($form['x']);
	$form['y'] = floatval($form['y']);
	$form['r'] = floatval($form['r']);
	$result = ($form['x'] * (($form['r']/100)/12) * ((1+(($form['r']/100)/12))**($form['y']*12)))/((((1+($form['r']/12/100))**($form['y']*12)))-1);

}
$x = null;
$y = null;
$r = null;
$result = null;
$messages = array();

getParams($form);
if ( validate($form,$infos,$messages,$hide_intro) ){
	process($form,$infos,$messages,$result);

}
$smarty = new Smarty();

$smarty->assign('app_url',_APP_URL);
$smarty->assign('root_path',_ROOT_PATH);
$smarty->assign('page_title','Kalkulator Kredytowy');
$smarty->assign('page_description','Profesjonalne szablonowanie oparte na bibliotece Smarty');
$smarty->assign('page_header','Szablon Smarty');

//pozostałe zmienne niekoniecznie muszą istnieć, dlatego sprawdzamy aby nie otrzymać ostrzeżenia
$smarty->assign('form',$form);
$smarty->assign('result',$result);
$smarty->assign('messages',$messages);
$smarty->assign('infos',$infos);

// 5. Wywołanie szablonu
$smarty->display(_ROOT_PATH.'/app/calc.html');