<?php
include_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$accountId = $_POST['accountId'];
	
    try {
		$leverateCrm = getCrm($config);
		
		$request = new UpdateAccountDetailsRequest();
		
		$request->AccountId = $accountId;
		$request->ZipCode = "010102";

		$dynamicAttributeString1 = new DynamicAttributeInfo();
		$dynamicAttributeString1->Name = 'new_testdynamicfield';		
		$dynamicAttributeString1->DynamicAttributeType = 'String';				
		$dynamicAttributeString1->Value = new SoapVar("Sample Value 1", XSD_STRING, "string", "http://www.w3.org/2001/XMLSchema");	

		$dynamicAttributeString2 = new DynamicAttributeInfo();
		$dynamicAttributeString2->Name = 'new_testdynamicfield2';		
		$dynamicAttributeString2->DynamicAttributeType = 'String';				
		$dynamicAttributeString2->Value = new SoapVar("Other sample value", XSD_STRING, "string", "http://www.w3.org/2001/XMLSchema");	

		$request->AdditionalAttributes = array($dynamicAttributeString1, $dynamicAttributeString2);
		
		$details = new UpdateAccountDetails();

		$details->ownerUserId = $config['ownerUserId'];
		$details->organizationName = $config['organization'];
		$details->businessUnitName = $config['businessUnitName'];
		$details->updateAccountDetailsRequest = $request;		
		
		$response = $leverateCrm->UpdateAccountDetails($details);
		
        $ResultInfo = new ResultInfo();
        $ResultInfo = $response->UpdateAccountDetailsResult->Result;
		
		$accountInfo = $response->UpdateAccountDetailsResult->AccountInfo;
		
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

view('UpdateAccountDetails', array('pageTitle' => 'Update account details',
	'result' => $result,
    'isResultSuccess' => $success,
    'errorMessage' => $message,
	'accountInfo' => $accountInfo));
