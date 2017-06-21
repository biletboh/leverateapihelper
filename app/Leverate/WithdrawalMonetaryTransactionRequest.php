<?php
include_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $amount = $_POST['amount'];
    $tradingPlatformAccountId = $_POST['tradingPlatformAccountId'];
	$withdrawalReasonValue = $_POST['withdrawalReasonValue'];
	$shouldAutoApproveBool = isset($_POST['shouldAutoApprove']);
	$updateTPOnApproveBool = isset($_POST['updateTPOnApprove']);


    try {
		$leverateCrm = getCrm($config);
		
		$info = new MonetaryTransactionRequestInfo();
		$info->Amount = $amount;		
		//$info->InternalComment = 'comment';		
		$info->OriginalAmount = null;
		$info->OriginalCurrency = null;
		$info->PaymentInfo = new CashPaymentInfo();
		$info->TradingPlatformAccountId = $tradingPlatformAccountId;
		//$info->TransactionReference = ...;

		$request = new WithdrawalMonetaryTransactionRequest();
		$request->AdditionalAttributes = array();
		$request->IsCancellationTransaction = false;
		$request->MonetaryTransactionRequestInfo = $info;
		$request->ShouldAutoApprove = $shouldAutoApproveBool;
		$request->UpdateTPOnApprove = $updateTPOnApproveBool;
		$request->WithdrawalCompleteTradingRequest = false;
		$request->WithdrawalHasDocuments = false;
		$request->WithdrawalHasEnoughFundsInTradingPlatform = false;
		$request->WithdrawalIsMethodOfPaymentSuitable = false;
		$request->WithdrawalManagementApproval = false;
		$request->WithdrawalPaid = false;
		//$request->WithdrawalPaymentDetails = null;

		$withdrawalReasonPickListInfo = new PickListInfo();
		$withdrawalReasonPickListInfo->Value = $withdrawalReasonValue;
		$withdrawalReasonPickListInfo->Name = 'SOMETHING';

		$request->WithdrawalReason = $withdrawalReasonPickListInfo;
		//$request->WithdrawalRequestDate = datetime;
		$request->WithdrawalRetentionOwnerApproval = false;
		$request->WithdrawalStatus = null;

		$query = new CreateMonetaryTransaction();
		$query->ownerUserId = $config['ownerUserId'];
		$query->organizationName = $config['organization'];
		$query->businessUnitName = $config['businessUnitName'];
		$query->monetaryTransactionRequest = $request;

		$response = $leverateCrm->CreateMonetaryTransaction($query);
		
        $ResultInfo = new ResultInfo();
        $ResultInfo = $response->CreateMonetaryTransactionResult->Result;
		
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
    $pickListInfoItems = new GetPickListInfoItems();
    $pickListInfoItems->businessUnitName = $config['businessUnitName'];
    $pickListInfoItems->organizationName = $config['organization'];
    $pickListInfoItems->ownerUserId = $config['ownerUserId'];
	$pickListInfoItems->entityName = 'lv_monetarytransaction';
	$pickListInfoItems->pickListAttributeName = 'lv_withdrawalreason';

    $leverateCrm = getCrm($config);

    $pickListInfoItemsResponse = new GetPickListInfoItemsResponse();
    $pickListInfoItemsResponse = $leverateCrm->GetPickListInfoItems($pickListInfoItems);

    $withdrawalReasons = $pickListInfoItemsResponse->GetPickListInfoItemsResult;
	
	} catch (Exception $e) {
		echo  '<div style="color:#ff0000;">Caught exception: '.  print_r($e). "\n</div>";
	}

view('WithdrawalMonetaryTransactionRequest', array('pageTitle' => 'Create monetary transaction for withdrawal',
	'result' => $result,
    'isResultSuccess' => $success,
    'errorMessage' => $message,
	'withdrawalReasons' => $withdrawalReasons->PickListInfo));