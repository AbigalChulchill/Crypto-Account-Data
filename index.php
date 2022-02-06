<html><head><style>
body { background-color:#ccc; color:#333; }
</style></head><body>
<?php

// SET UP CCXT
date_default_timezone_set('UTC');
include "../ccxt/ccxt.php";
//var_dump (\ccxt\Exchange::$exchanges); // print a list of all available exchange classes for test

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
	// https://www.freqtrade.io/en/stable/exchanges/#using-subaccounts
	// https://github.com/ccxt/ccxt/issues/6513
	'headers' => array(
		'FTX-SUBACCOUNT' => 'SUB-ACCOUNT-NAME',),
    'apiKey' => 'FTX-READ-ONLY-API-KEY-SUB-ACCOUNT',
    'secret' => 'FTX-READ-ONLY-API-SECRET-MAIN-ACCOUNT',
));
$exchange3 = new $exchange_class(array(
	'headers' => array(
		'FTX-SUBACCOUNT' => 'SUB-ACCOUNT-NAME',),
    'apiKey' => 'FTX-READ-ONLY-API-KEY-MAIN-ACCOUNT',
    'secret' => 'FTX-READ-ONLY-API-SECRET-MAIN-ACCOUNT',
));

// NEEDED DUE TO AN ISSUE WITH PHP 7 AND MAC OS
function json ($data, $params = array ()) {
	$options = array ( 'convertArraysToObjects' => JSON_FORCE_OBJECT); // other flags if needed...
	$flags = 0;
	foreach ($options as $key => $value) if (array_key_exists ($key, $params) && $params[$key]) $flags |= $options[$key];
	$encoded = json_encode ($data, $flags);
	return json_decode ($encoded, TRUE);
}
// GET DATA FROM THE EXCHANGE AND PROCESS IT
$acct = json($exchange->private_get_account());
$acct2 = json($exchange2->private_get_account());
$acct3 = json($exchange3->private_get_account());
$bals = json($exchange->fetch_balance());
$bals2 = json($exchange2->fetch_balance());
$bals3 = json($exchange3->fetch_balance());
$pos1 = json($exchange->fetch_positions());
$pos2 = json($exchange2->fetch_positions());
$pos3 = json($exchange3->fetch_positions());

// DATA IN A TABLE, SO WE CAN MAKE A SINGLE CALL IN SHEETS, TO PULL ALL DATA INTO ROWS AND COLUMNS
echo('<table>');

// WRITE ACCOUNT SUMMARIES FOR MAIN ACCOUNT AND 2 SUB ACCOUNTS
function writeAccounts($which){
	global $acct, $acct2, $acct3;
	echo('<tr><td>'.$which.': </td>');
	// MAIN ACCOUNT
	echo('<td>'.$acct['result'][$which].'</td>');
	// SUB ACCOUNT 2
	echo('<td>'.$acct2['result'][$which].'</td>');
	// SUB ACCOUNT 3
	echo('<td>'.$acct3['result'][$which].'</td>');
	echo('</tr>');
}
function writeBalances($ticker,$which){
	global $bals, $bals2, $bals3;
	echo('<tr><td>'.$ticker.' '.$which.': </td>');
	// MAIN ACCOUNT
	echo('<td>'.$bals[$ticker][$which].'</td>');
	// SUB ACCOUNT 2
	echo('<td>'.$bals2[$ticker][$which].'</td>');
	// SUB ACCOUNT 3
	echo('<td>'.$bals3[$ticker][$which].'</td>');
	echo('</tr>');
}

writeAccounts('collateral');
writeAccounts('freeCollateral');
writeAccounts('totalPositionSize');
writeBalances('USD','total');
writeBalances('BTC','free');
writeBalances('ETH','free');

echo('<tr><td></td></tr>');

// WRITE DATA FOR PERPS AND FUTURES POSITIONS
function writePosition($ticker,$account){
	global $pos1, $pos2, $pos3;
	$lPos = ${'pos' . $account};
	$arrayLength = count($lPos);
	$i=0;
	while ($i < $arrayLength){
		if (($lPos[$i]['info']['future'] == $ticker) || ($ticker == 'header')){
			echo('<tr>');
			if($ticker=='header'){$ticker = '-';}			
			echo('<td>'.$account.'-'.$ticker.'</td>');

			// ADD OR REMOVE DATA POINTS
			writeTd($ticker,'markPrice',$lPos,$i,1);
			writeTd($ticker,'recentAverageOpenPrice',$lPos,$i,2);
			writeTd($ticker,'entryPrice',$lPos,$i,1);
			writeTd($ticker,'recentPnl',$lPos,$i,2);
			writeTd($ticker,'unrealizedPnl',$lPos,$i,1);
			writeTd($ticker,'recentBreakEvenPrice',$lPos,$i,2);
			writeTd($ticker,'realizedPnl',$lPos,$i,2);

			writeTd($ticker,'collateralUsed',$lPos,$i,2);
			writeTd($ticker,'estimatedLiquidationPrice',$lPos,$i,2);
			writeTd($ticker,'initialMargin',$lPos,$i,1);
			writeTd($ticker,'initialMarginPercentage',$lPos,$i,1);

			writeTd($ticker,'size',$lPos,$i,2);
			writeTd($ticker,'cumulativeBuySize',$lPos,$i,2);
			writeTd($ticker,'cumulativeSellSize',$lPos,$i,2);
			writeTd($ticker,'size',$lPos,$i,2);
			writeTd($ticker,'side',$lPos,$i,2);
			writeTd($ticker,'cost',$lPos,$i,2);
			writeTd($ticker,'longOrderSize',$lPos,$i,2);
			writeTd($ticker,'shortOrderSize',$lPos,$i,2);

			echo('</tr>');
			if($ticker=='-'){
				break;
			}
		}
	$i++;
	}
}
function writeTd($ticker,$info,$lPos,$i,$type){
	if($type==1){${$info} = $lPos[$i][$info];}
	else{${$info} = $lPos[$i]['info'][$info];}
	if(!isset(${$info})){${$info} = '0';}
	if($ticker=='-'){${$info} = $info;}
	echo('<td>'.${$info}.'</td>');
}

writePosition('header','1');

// MAIN ACCOUNT POSITIONS
// ADD WHATEVER TICKERS NEEDED
writePosition('BTC-PERP','1');
writePosition('BTC-20210625','1');
writePosition('ETH-PERP','1');
writePosition('ETH-20211231','1');
writePosition('SOL-PERP','1');
writePosition('SOL-0325','1');
writePosition('SOL-20210625','1');
writePosition('SOL-20211231','1');
writePosition('AVAX-PERP','1');
writePosition('AVAX-20211231','1');
writePosition('FTM-PERP','1');
writePosition('LUNA-PERP','1');
writePosition('RUNE-PERP','1');
writePosition('LINK-PERP','1');
writePosition('LINK-20210326','1');
writePosition('KSM-PERP','1');
writePosition('AXS-PERP','1');
writePosition('NEAR-PERP','1');
writePosition('SNX-PERP','1');
writePosition('SRM-PERP','1');
writePosition('COMP-PERP','1');
writePosition('DOGE-20210326','1');
writePosition('POLIS-PERP','1');
writePosition('ADA-PERP','1');
writePosition('MID-PERP','1');
writePosition('MID-20210625','1');
writePosition('DEFI-PERP','1');

echo('<tr><td></td></tr>');

// SUB ACCOUNT POSITIONS
// ADD WHATEVER TICKERS NEEDED
writePosition('BTC-PERP','2');
writePosition('BTC-0325','2');
writePosition('ETH-PERP','2');
writePosition('SOL-PERP','2');
writePosition('SOL-0325','2');
writePosition('SOL-20211231','2');
writePosition('FTM-PERP','2');
writePosition('AVAX-PERP','2');
writePosition('AVAX-20211231','2');
writePosition('NEAR-PERP','2');
writePosition('LINK-PERP','2');
writePosition('BAND-PERP','2');
writePosition('YFI-PERP','2');
writePosition('SUSHI-PERP','2');
writePosition('ALGO-PERP','2');
writePosition('ADA-PERP','2');
writePosition('DOT-PERP','2');
writePosition('MID-PERP','2');

echo('<tr><td></td></tr>');

// SUB ACCOUNT POSITIONS
// ADD WHATEVER TICKERS NEEDED
writePosition('NEAR-PERP','3');

echo('</table>');


// PRINT OUT RAW DATA FOR SETTING UP OR CHECKING EXCHANGE OUTPUT
function var_dump_pre($mixed = null) {
	global $bals, $bals2, $bals3;
	global $pos1, $pos2, $pos3;
	global $acct, $acct2, $acct3;
	global $test;
	echo '<pre>';
	var_dump($pos1);
//	var_dump($acct);
//	global $subbals;
//	print_r(array_keys($balance, 1));
	echo '</pre>';
  return null;
}
var_dump_pre();

?>
</body>
</html>