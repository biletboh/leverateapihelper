<?php
$config = array(
    'wsdl' => 'http://credotrade.stg-tradingcrm.com:8093/mex',
    'apiLocation' => 'https://credotrade.stg-tradingcrm.com:8094/CrmServiceBasic',
    'organization' => 'CredoTrade',
    'businessUnitName' => 'CredoTrade',
    'ownerUserId' => '75199795-5A4F-E711-80EF-005056B11D9A',
    'username' => 'credotrade',
    'password' => '9L0MZdmd9p',
    'tradingPlatforms' => array(
        'DEMO' => array(
            'name' => 'CredoTrade MetaTrader Demo',
            'id' => '86199795-5A4F-E711-80EF-005056B11D9A'),
        'REAL' => array(
            'name' => 'CredoTrade MetaTrader Real',
            'id' =>  '88199795-5A4F-E711-80EF-005056B11D9A',
        ),
    ),
    'wsdlCache' => WSDL_CACHE_MEMORY
);
