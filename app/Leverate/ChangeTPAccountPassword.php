<?php
include_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {   
   $tradingPlatformAccountName = $_POST['tradingPlatformAccountName'];
	 $tradingPlatformAccountOldPassword = $_POST['oldPassword'];
	 $tradingPlatformAccountNewPassword = $_POST['newPassword'];
    try {      			
			  $request = new ChangePassword();       
				$request->ownerUserId = $config['ownerUserId'];
				$request->organizationName = $config['organization'];
				$request->businessUnitName = $config['businessUnitName'];
				$request->tradingPlatformAccountName = $tradingPlatformAccountName;
				$request->tradingPlatformAccountOldPassword = $tradingPlatformAccountOldPassword;
				$request->tradingPlatformAccountNewPassword = $tradingPlatformAccountNewPassword;

				$leverateCrm = getCrm($config);

        $response = new ChangePasswordResponse();
        $response =$leverateCrm->ChangePassword($request);
        $result = $response->ChangePasswordResult;
       
        $success = $result->Code;
        $message = $result->Message;


    } catch (Exception $e) {
        echo  '<div style="color:#ffffff;">Caught exception: '.  $e. "\n</div>";
    }

} else {
    $result = null;
    $success = true;
    $message = null;
}
view('ChangeTPAccountPassword', array(
	'pageTitle' => 'Change trading platform account password',
	'result' => $result,
	'resultCode' => $success,
	'message' => $message,
));
