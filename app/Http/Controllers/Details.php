<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;


class Details extends Controller
{
    /**
     * Show the account details for the given user.
     *
     * @return Response
     */


    public function __invoke()
    {

        include_once(app_path().'/Leverate/config.php');
        include_once(app_path().'/Leverate/init.php');

        $request= getAccountDetailsRequest();
        $request->FilterType = 'Email';
        $request->FilterValue = 'biletskyboh@gmail.com';
        $request->AdditionalAttributesNames = array('new_testdynamicfield', 'new_testdynamicfield2');

        $getAccountDetails = getAccountDetails();
        $getAccountDetails->ownerUserId = $config['ownerUserId'];
        $getAccountDetails->organizationName = $config['organization'];
        $getAccountDetails->businessUnitName = $config['businessUnitName'];
        $getAccountDetails->accountDetailsRequest = $request;

        $leverateCrm = getCrm($config);

        $response = getAccountDetailsResponse();
        $response = $leverateCrm->GetAccountDetails($getAccountDetails);
        
        $result = $response->GetAccountDetailsResult;
        
        $ResultInfo = getResultInfo();
        $ResultInfo = $result->Result;

        $result = $result->AccountsInfo->AccountInfo[0];
        $success = $ResultInfo->Code;
        $message = $ResultInfo->Message;
        return ['success' => $success, 'message' => $message, 'result' => $result];
    }
}
