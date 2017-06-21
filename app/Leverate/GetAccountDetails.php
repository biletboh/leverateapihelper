<?php
include_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    try {
        $ownerUserId = new guid();
        $ownerUserId = $config['ownerUserId'];

        $request = new AccountDetailsRequest();
        $request->FilterType = 'Email';
        $request->FilterValue = $email;

        $request->AdditionalAttributesNames = array('new_testdynamicfield', 'new_testdynamicfield2');

        $getAccountDetails = new GetAccountDetails();
        $getAccountDetails->ownerUserId = $ownerUserId;
        $getAccountDetails->organizationName = $config['organization'];
        $getAccountDetails->businessUnitName = $config['businessUnitName'];
        $getAccountDetails->accountDetailsRequest = $request;

        $leverateCrm = getCrm($config);

        $response = new GetAccountDetailsResponse();
        $response =$leverateCrm->GetAccountDetails($getAccountDetails);
	
        $result = $response->GetAccountDetailsResult;

        $ResultInfo = new ResultInfo();
        $ResultInfo = $result->Result;

        $result = $result->AccountsInfo;
        $success = $ResultInfo->Code;
        $message = $ResultInfo->Message;

    } catch (Exception $e) {
        echo  '<div style="color:#ffffff;">Caught exception: '.  $e. "\n</div>";
    }

} else {
    $result = null;
    $success = true;
    $message = null;
}
view('GetAccountDetails', array(
    'pageTitle' => 'Get Account Details',
    'result' => $response,
    'isResultSuccess' => $success,
    'errorMessage' => $message,
));
