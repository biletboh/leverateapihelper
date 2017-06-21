<?php
include_once(app_path() . '/Leverate/config.php');
include_once(app_path() . '/Leverate/SoapProxy.php');
include_once(app_path() . '/Leverate/LeverateCrm.php');

function getCrm($config) {
    return new LeverateCrm($config['wsdl'], array(
        'login' => $config['username'],
        'password' => $config['password'],
        'location' => $config['apiLocation'],
        'encoding'=>'UTF-8',
        'cache_wsdl' => $config['wsdlCache'],
        'trace' => true,
        //'proxy_host' => '127.0.0.1',
        //'proxy_port' => 8888,
    ));
}

function getAccountDetailsRequest() {
    return new AccountDetailsRequest();
}

function getAccountDetails() {
    return new GetAccountDetails();
}

function getAccountDetailsResponse() {
    return new GetAccountDetailsResponse();
}

function getResultInfo() {
    return new ResultInfo();
}
