<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

// 1. pobranie parametrów

$kwotaKredytu = $_REQUEST ['kwotaKredytu'];
$oprocentowanie = $_REQUEST ['oprocentowanie'];
$okres = $_REQUEST ['okres'];
$raty = $_REQUEST ['raty'];
$prowizja = $_REQUEST ['prowizja'];

// 2. walidacja parametrów z przygotowaniem zmiennych dla widoku

// sprawdzenie, czy parametry zostały przekazane
if ( ! (isset($kwotaKredytu) && isset($oprocentowanie) && isset($okres) && isset($raty) && isset($prowizja))) {
	//sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
	$messages [] = 'Błędne wywołanie aplikacji. Brak jednego z parametrów.';
}

// sprawdzenie, czy potrzebne wartości zostały przekazane
if ( $kwotaKredytu == "")
	$messages [] = 'Nie podano kwoty kredytu';
if ( $oprocentowanie == "")
	$messages [] = 'Nie podano oprocentowania';
if ( $okres == "")
    $messages [] = 'Nie podano okresu';
if ( $raty == "")
    $messages [] = 'Nie podano liczby rat';
if ( $prowizja == "")
    $messages [] = 'Nie podano prowizji';

//nie ma sensu walidować dalej gdy brak parametrów
if (empty( $messages )) {
	
	// sprawdzenie, czy $x i $y są liczbami całkowitymi
	if (! is_numeric( $kwotaKredytu ))
		$messages [] = '"okresKredytu" nie jest liczbą całkowitą';
	
	if (! is_numeric( $okres ))
		$messages [] = '"okres" nie jest liczbą całkowitą';

    if (! is_numeric( $raty ))
        $messages [] = '"raty" nie jest liczbą całkowitą';

    $pattern = "/^(?:100(?:\.0(?:0)?)?|\d{1,2}(?:\.\d{1,2})?)$/";

    if (!preg_match($pattern, $oprocentowanie))
        $messages [] = 'nie podano wartosci procentowej w "oprocentowanie"';

    if (!preg_match($pattern, $prowizja))
        $messages [] = 'nie podano wartosci procentowej w "prowizja"';

}

// 3. wykonaj zadanie jeśli wszystko w porządku

if (empty ( $messages )) { // gdy brak błędów
	
	//konwersja parametrów
    $kwotaKredytu = intval($kwotaKredytu);
    $oprocentowanie = doubleval($oprocentowanie);
    $okres = intval($okres);
    $raty = intval($raty);
    $prowizja = doubleval($prowizja);
	
	//wykonanie raty i kosztu kredytu
    $q = 1 + ($oprocentowanie / $raty);
    $kwotaRaty = $kwotaKredytu * $q * $raty * ($q - 1) / $q * 12 - 1;
    $kosztKredytu = $q * ($raty * $okres) + ($prowizja / 100) * $kwotaKredytu - $kwotaKredytu;
}

// 4. Wywołanie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$x,$y,$operation,$result)
//   będą dostępne w dołączonym skrypcie
include 'calc_view.php';