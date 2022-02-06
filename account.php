<?php

$exchange_id = 'ftx';
$exchange_class = "\\ccxt\\$exchange_id";

// MAIN ACCOUNT
$exchange = new $exchange_class(array(
    'apiKey' => 'FTX-READ-ONLY-API-KEY-MAIN-ACCOUNT',
    'secret' => 'FTX-READ-ONLY-API-SECRET-MAIN-ACCOUNT',
));

// SUB ACCOUNT: 2
$exchange2 = new $exchange_class(array(
	'headers' => array(
		'FTX-SUBACCOUNT' => 'SUB-ACCOUNT-NAME',),
    'apiKey' => 'FTX-READ-ONLY-API-KEY-SUB-ACCOUNT',
    'secret' => 'FTX-READ-ONLY-API-SECRET-SUB-ACCOUNT',
));

// SUB ACCOUNT: 3
$exchange3 = new $exchange_class(array(
	'headers' => array(
		'FTX-SUBACCOUNT' => 'SUB-ACCOUNT-NAME',),
    'apiKey' => 'FTX-READ-ONLY-API-KEY-SUB-ACCOUNT',
    'secret' => 'FTX-READ-ONLY-API-SECRET-SUB-ACCOUNT',
));

?>