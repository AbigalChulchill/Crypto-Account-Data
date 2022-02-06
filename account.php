<?php

// SET UP EXCHANGE CONNECTION
$exchange_id = 'ftx';
$exchange_class = "\\ccxt\\$exchange_id";
// TO MAIN ACCOUNT
$exchange = new $exchange_class(array(
    'apiKey' => 'FTX-READ-ONLY-API-KEY-MAIN-ACCOUNT',
    'secret' => 'FTX-READ-ONLY-API-SECRET-MAIN-ACCOUNT',
));
// AND ANY SUB ACCOUNTS
$exchange2 = new $exchange_class(array(
	'headers' => array(
		'FTX-SUBACCOUNT' => 'SUB-ACCOUNT-NAME',),
    'apiKey' => 'FTX-READ-ONLY-API-KEY-SUB-ACCOUNT',
    'secret' => 'FTX-READ-ONLY-API-SECRET-SUB-ACCOUNT',
));
$exchange3 = new $exchange_class(array(
	'headers' => array(
		'FTX-SUBACCOUNT' => 'SUB-ACCOUNT-NAME',),
    'apiKey' => 'FTX-READ-ONLY-API-KEY-SUB-ACCOUNT',
    'secret' => 'FTX-READ-ONLY-API-SECRET-SUB-ACCOUNT',
));

?>