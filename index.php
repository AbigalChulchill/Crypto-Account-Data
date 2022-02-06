<html><head><style>
body { background-color:#ccc; color:#333; }
</style></head><body>
<?php

// FTX Account To Spread Sheet
// Version 1.0
// https://github.com/tom9000/FTX-Account-To-Spread-Sheet

// SET UP CCXT
date_default_timezone_set('UTC');
include 'ccxt/ccxt.php';
//var_dump (\ccxt\Exchange::$exchanges); // print a list of all available exchange classes for test

include 'account.php';

// NEEDED DUE TO AN ISSUE WITH PHP 7 AND MAC OS
function json ($data, $params = array ()) {
	$options = array ( 'convertArraysToObjects' => JSON_FORCE_OBJECT); // other flags if needed...
	$flags = 0;
	foreach ($options as $key => $value) if (array_key_exists ($key, $params) && $params[$key]) $flags |= $options[$key];
	$encoded = json_encode ($data, $flags);
	return json_decode ($encoded, TRUE);
}

// IF YOU DON'T HAVE SUB ACCOUNTS, THEN COMMENT OR DELETE THESE LINES
$acct = json($exchange->private_get_account()); // MAIN ACCOUNT
$acct2 = json($exchange2->private_get_account()); // SUB ACCOUNT: 2
$acct3 = json($exchange3->private_get_account()); // SUB ACCOUNT: 3
$bals = json($exchange->fetch_balance()); // MAIN ACCOUNT
$bals2 = json($exchange2->fetch_balance()); // SUB ACCOUNT: 2
$bals3 = json($exchange3->fetch_balance()); // SUB ACCOUNT: 3
$pos1 = json($exchange->fetch_positions()); // MAIN ACCOUNT
$pos2 = json($exchange2->fetch_positions()); // SUB ACCOUNT: 2
$pos3 = json($exchange3->fetch_positions()); // SUB ACCOUNT: 3

echo('<table>');

// IF YOU DON'T HAVE SUB ACCOUNTS, THEN COMMENT OR DELETE THESE LINES
function writeAccounts($which){
	global $acct, $acct2, $acct3;
	echo('<tr><td>'.$which.': </td>');
	echo('<td>'.$acct['result'][$which].'</td>'); // MAIN ACCOUNT
	echo('<td>'.$acct2['result'][$which].'</td>'); // SUB ACCOUNT: 2
	echo('<td>'.$acct3['result'][$which].'</td>'); // SUB ACCOUNT: 3
	echo('</tr>');
}
function writeBalances($ticker,$which){
	global $bals, $bals2, $bals3;
	echo('<tr><td>'.$ticker.' '.$which.'</td>');
	echo('<td>'.$bals[$ticker][$which].'</td>'); // MAIN ACCOUNT
	echo('<td>'.$bals2[$ticker][$which].'</td>'); // SUB ACCOUNT: 2
	echo('<td>'.$bals3[$ticker][$which].'</td>'); // SUB ACCOUNT: 3
	echo('</tr>');
}



// CHANGE ACCOUNT DATA AS NEEDED
// ALSO AVAILABLE: totalAccountValue, marginFraction, openMarginFraction, etc
writeAccounts('collateral');
writeAccounts('freeCollateral');
writeAccounts('totalPositionSize');

// CHANGE OR ADD COIN BALANCES AS NEEDED
writeBalances('USD','total');
writeBalances('BTC','total');
writeBalances('ETH','total');



echo('<tr><td>&nbsp;</td></tr>');
echo('<tr><td>&nbsp;</td></tr>');
echo('<tr><td>&nbsp;</td></tr>');

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




			// CHANGE, ADD OR REMOVE DATA POINTS FOR POSITIONS
			writeTd($ticker,$lPos,$i,1,'markPrice');
			writeTd($ticker,$lPos,$i,2,'recentAverageOpenPrice');
			writeTd($ticker,$lPos,$i,1,'entryPrice');
			writeTd($ticker,$lPos,$i,2,'recentPnl');
			writeTd($ticker,$lPos,$i,1,'unrealizedPnl');
			writeTd($ticker,$lPos,$i,2,'recentBreakEvenPrice');
			writeTd($ticker,$lPos,$i,2,'realizedPnl');

			writeTd($ticker,$lPos,$i,2,'collateralUsed');
			writeTd($ticker,$lPos,$i,2,'estimatedLiquidationPrice');
			writeTd($ticker,$lPos,$i,1,'initialMargin');
			writeTd($ticker,$lPos,$i,1,'initialMarginPercentage');

			writeTd($ticker,$lPos,$i,2,'size');
			writeTd($ticker,$lPos,$i,2,'cumulativeBuySize');
			writeTd($ticker,$lPos,$i,2,'cumulativeSellSize');
			writeTd($ticker,$lPos,$i,2,'size');
			writeTd($ticker,$lPos,$i,2,'side');
			writeTd($ticker,$lPos,$i,2,'cost');
			writeTd($ticker,$lPos,$i,2,'longOrderSize');
			writeTd($ticker,$lPos,$i,2,'shortOrderSize');




			echo('</tr>');
			if($ticker=='-'){
				break;
			}
		}
	$i++;
	}
}
function writeTd($ticker,$lPos,$i,$type,$info){
	if($type==1){${$info} = $lPos[$i][$info];}
	else{${$info} = $lPos[$i]['info'][$info];}
	if(!isset(${$info})){${$info} = '0';}
	if($ticker=='-'){${$info} = $info;}
	echo('<td>'.${$info}.'</td>');
}


writePosition('header','1');



// CHANGE OR ADD POSITIONS BY TICKER

// MAIN ACCOUNT POSITIONS
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



echo('<tr><td>&nbsp;</td></tr>');



// SUB ACCOUNT 2 POSITIONS
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



echo('<tr><td>&nbsp;</td></tr>');



// SUB ACCOUNT 3 POSITIONS
writePosition('BTC-PERP','3');
writePosition('NEAR-PERP','3');



echo('</table>');



// PRINT OUT RAW DATA TO CHECK EXCHANGE OUTPUT AVAILABLE
function var_dump_pre($mixed = null) {
	global $bals, $bals2, $bals3;
	global $pos1, $pos2, $pos3;
	global $acct, $acct2, $acct3;
	global $test;
	echo '<pre>';
//	var_dump($pos1);
	var_dump($acct);
//	global $subbals;
//	print_r(array_keys($balance, 1));
	echo '</pre>';
  return null;
}
var_dump_pre();


?>
</body>
</html>