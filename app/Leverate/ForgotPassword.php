<?php
include_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tradingPlatformAccountName = $_POST['tradingPlatformAccountName'];
    $email = $_POST['email'];
    try {
        $ownerUserId = new guid();
        $ownerUserId = $config['ownerUserId'];

        $forgotPassword = new ForgotYourPassword();

        $forgotPassword->ownerUserId = $ownerUserId;
        $forgotPassword->organizationName = $config['organization'];
        $forgotPassword->businessUnitName = $config['businessUnitName'];
        $forgotPassword->tradingPlatformAccountName = $tradingPlatformAccountName;
        $forgotPassword->email = $email;

        $leverateCrm = getCrm($config);

        $forgotPasswordResponse = new ForgotYourPasswordResponse();
        $forgotPasswordResponse = $leverateCrm->ForgotYourPassword($forgotPassword);
        $forgotPasswordResult = $forgotPasswordResponse->ForgotYourPasswordResult;

        $ResultInfo = new ResultInfo();
        $ResultInfo = $forgotPasswordResponse->ForgotYourPasswordResult;

        $result = $ResultInfo->Message;
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
view('ForgotPassword', array(
    'pageTitle' => 'Forgot Password',
    'result' => $result,
    'isResultSuccess' => ($success=='Success'),
    'errorMessage' => $message,
));
