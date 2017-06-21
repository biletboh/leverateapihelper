<?php
include_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$tradingPlatformAccountName = $_POST['tradingPlatformAccountName'];
	try {
		$leverateCrm = getCrm($config);
		
		$request = new GetTradingPlatformAccountBalance();
		$request->ownerUserId = $config['ownerUserId'];
		$request->organizationName = $config['organization'];
		$request->businessUnitName = $config['businessUnitName'];
		$request->tradingPlatformAccountName = $tradingPlatformAccountName;
		
		$response = $leverateCrm->GetTradingPlatformAccountBalance($request)->GetTradingPlatformAccountBalanceResult;
		
		$resultInfo = $response->Result;
		$tpAccountBalance = $response->TradingPlatformAccountBalance;
		$tpAccountId = $response->TradingPlatformAccountId;
		
		$balance = $tpAccountBalance->Balance;
		$credit = $tpAccountBalance->Credit;
		$margin = $tpAccountBalance->Margin;
		$equity = $tpAccountBalance->Equity;

		$requestId = $resultInfo->RequestId;
		$success = $resultInfo->Code;
		$message = $resultInfo->Message;
		$result = $tpAccountBalance;
	} catch (Exception $e) {
		echo '<div style="color:#ff0000;">Caught exception: '. print_r($e). "\n</div>";
	}


} else {
	$result = null;
	$success = true;
	$message = null;
}

view('GetTPAccountBalance', array(
	'pageTitle' => 'Get trading platform account balance',
	'requestId' => $requestId,
	'isResultSuccess' => $success,
	'message' => $message,
	'result' => $result,
	'tpAccountId' => $tpAccountId,
	'balance' => $balance,
	'credit' => $credit, 
	'margin' => $margin, 
	'equity' => $equity,
));
