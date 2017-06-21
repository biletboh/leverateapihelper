<?php
include_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tradingPlatformAccountName = $_POST['tradingPlatformAccountName'];
    $password = $_POST['password'];
    try {
        $ownerUserId = new guid();
        $ownerUserId = $config['ownerUserId'];


        $loginAccount = new LoginAccount();

        $loginAccount->ownerUserId = $ownerUserId;
        $loginAccount->organizationName = $config['organization'];
        $loginAccount->businessUnitName = $config['businessUnitName'];
        $loginAccount->tradingPlatformAccountName = $tradingPlatformAccountName;
        $loginAccount->tradingPlatformAccountPassword = $password;

        $leverateCrm = getCrm($config);

        $loginAccountResponse = new LoginAccountResponse();
        $loginAccountResponse = $leverateCrm->LoginAccount($loginAccount);
        $loginResponse = $loginAccountResponse->LoginAccountResult;
        $ResultInfo = new ResultInfo();
        $ResultInfo = $loginResponse->Result;

        $result = $loginResponse->AccountId;
        $success = $ResultInfo->Code;
        $message = $ResultInfo->Message;

    } catch (Exception $e) {
        echo  '<div style="color:#ffffff;">Caught exception: '.  $e. "\n</div>";
    }

} else {
    $result = null;
    $success = 'Success';
    $message = null;
}
view('LoginAccount', array(
    'pageTitle' => 'Login Account',
    'result' => $result,
    'isResultSuccess' => ($success=='Success'),
    'errorMessage' => $message,
));
