<?php
include_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tradingPlatformAccountId = $_POST['tradingPlatformAccountId'];
    $amount = $_POST['amount'];


    try {
		 $leverateCrm = getCrm($config);
		
		$info = new MonetaryTransactionRequestInfo();
		$info->Amount = $amount;		
		$info->TradingPlatformAccountId = $tradingPlatformAccountId;
		$info->PaymentInfo = new CashPaymentInfo();
		$info->OriginalAmount = null;
		$info->OriginalCurrency = null; 
				
		$dynamicAttributeInt = new DynamicAttributeInfo();
		$dynamicAttributeInt->Name = 'lv_withdrawalreason';		
		$dynamicAttributeInt->DynamicAttributeType = 'Picklist';				
		$dynamicAttributeInt->Value = new SoapVar(3, XSD_INT, "int", "http://www.w3.org/2001/XMLSchema");	
		
		$dynamicAttributeString = new DynamicAttributeInfo();
		$dynamicAttributeString->Name = 'lv_internalcomment';		
		$dynamicAttributeString->DynamicAttributeType = 'String';				
		$dynamicAttributeString->Value = new SoapVar("some string", XSD_STRING, "string", "http://www.w3.org/2001/XMLSchema");	
		
		$dynamicAttributeBit = new DynamicAttributeInfo();
		$dynamicAttributeBit->Name = 'lv_managementapproval';		
		$dynamicAttributeBit->DynamicAttributeType = 'Bit';				
		$dynamicAttributeBit->Value = new SoapVar(true, XSD_BOOLEAN, "boolean", "http://www.w3.org/2001/XMLSchema");		

		$request = new DepositRequest();
		$request->IsCancellationTransaction = false;
		$request->ShouldAutoApprove = true;
		$request->UpdateTPOnApprove = true;
		$request->MonetaryTransactionRequestInfo = $info;
		$request->AdditionalAttributes = array($dynamicAttributeInt, $dynamicAttributeString, $dynamicAttributeBit);

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
/*
		if ($result->Result->Code != 'Success') {
			return false;
		}
*/
    } catch (Exception $e) {
        echo  '<div style="color:#ff0000;">Caught exception: '.  print_r($e). "\n</div>";
    }

} else {
    $result = null;
    $success = 'Success';
    $message = null;
}

view('CreateMonetaryTransaction', array('pageTitle' => 'Create monetary transaction',
	'result' => $result,
    'isResultSuccess' => $success,
    'errorMessage' => $message));
