<?php
include_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$accountId = $_POST['accountId'];
	$tradingPlatformAccountName = $_POST['tradingPlatformAccountName'];
	$caseTitle = $_POST['caseTitle'];
	$description = $_POST['description'];
	
    $amount = $_POST['amount'];
	$currencyId = $_POST['currencyId'];
	
    try {
		$leverateCrm = getCrm($config);

		$details = new WithdrawalRequest();
		$details->AccountId = $accountId;
		$details->Amount = $amount;
		$details->CaseTitle = $caseTitle;
		$details->CurrencyId = $currencyId;
		$details->Description = $description;
		$details->TradingPlatformAccountName = $tradingPlatformAccountName;

		$details->WithdrawalMethod = 'Cash';

		$request = new CreateWithdrawalRequest();
		$request->ownerUserId = $config['ownerUserId'];
		$request->organizationName = $config['organization'];
		$request->businessUnitName = $config['businessUnitName'];
		$request->withdrawalRequest = $details;
		
		print_r($request);
		
		$response = $leverateCrm->CreateWithdrawalRequest($request);
		
        $ResultInfo = new ResultInfo();
        $ResultInfo = $response->CreateWithdrawalRequestResult;
		
		$result = $ResultInfo->RequestId;
        $success = $ResultInfo->Code;
        $message = $ResultInfo->Message;

    } catch (Exception $e) {
        echo  '<div style="color:#ff0000;">Caught exception: '.  print_r($e). "\n</div>";
    }
		 
	
	
} else {
    $result = null;
    $success = 'Success';
    $message = null;
}

try {
    $gc = new GetCurrencies();
    $gc->businessUnitName = $config['businessUnitName'];
    $gc->organizationName = $config['organization'];
    $gc->ownerUserId = $config['ownerUserId'];

    $leverateCrm = getCrm($config);

    $CurrenciesResponse = new GetCurrenciesResponse();
    $CurrenciesResponse = $leverateCrm->GetCurrencies($gc);

    $currencies = $CurrenciesResponse->GetCurrenciesResult;
	
} catch (Exception $e) {
    print  'Caught exception: '.  $e. "\n";
    exit;
}

try {
    $pickListInfoItems = new GetPickListInfoItems();
    $pickListInfoItems->businessUnitName = $config['businessUnitName'];
    $pickListInfoItems->organizationName = $config['organization'];
    $pickListInfoItems->ownerUserId = $config['ownerUserId'];
	$pickListInfoItems->entityName = 'lv_monetarytransaction';
	$pickListInfoItems->pickListAttributeName = 'lv_cardtype';

    $leverateCrm = getCrm($config);

    $pickListInfoItemsResponse = new GetPickListInfoItemsResponse();
    $pickListInfoItemsResponse = $leverateCrm->GetPickListInfoItems($pickListInfoItems);

    $cardTypes = $pickListInfoItemsResponse->GetPickListInfoItemsResult;
	
} catch (Exception $e) {
    print  'Caught exception: '.  $e. "\n";
    exit;
}

view('CreateWithdrawalRequest', array('pageTitle' => 'Create withdrawal request',
	'result' => $result,
    'isResultSuccess' => $success,
    'errorMessage' => $message,
	'currencies' => $currencies->CurrencyInfo,
	'cardTypes' => $cardTypes->PickListInfo));
