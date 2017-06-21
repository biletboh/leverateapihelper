<?php
include_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$accountId = $_POST['accountId'];
	
    try {
		$leverateCrm = getCrm($config);
		
		$request = new GetTradingHistory();

		$request->ownerUserId = $config['ownerUserId'];
		$request->organizationName = $config['organization'];
		$request->businessUnitName = $config['businessUnitName'];
		$request->tradingPlatformAccountId = $accountId;
		$request->startTime = "2014-11-01";
		$request->endTime = "2014-11-27";
		$request->maxRows = 30;
		
		$response = $leverateCrm->GetTradingHistory($request);
		
        $ResultInfo = new ResultInfo();
        $ResultInfo = $response->GetTradingHistoryResult->Result;
		
		$closedPositionInfo = $responce->GetTradingHistoryResult->ClosedPositions;

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
	

view('GetTradingHistory', array('pageTitle' => 'Get trading history',
	'result' => $result,
    'isResultSuccess' => $success,
    'errorMessage' => $message,
	'closedPositionInfo' => $closedPositionInfo));
