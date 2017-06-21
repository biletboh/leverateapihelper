<?php
include_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $country = $_POST['countryId'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $phoneCountryCode = $_POST['phoneCountryCode'];
    $phoneAreaCode = $_POST['phoneAreaCode'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $groupName = $_POST['tradingPlatformGroupName'];

    try {
        $countryId = new guid();
        $countryId = $country;

        $ownerUserId = new guid();
        $ownerUserId = $config['ownerUserId'];

        $demoAccountRegistrationRequest = new DemoAccountRegistrationRequest();
        $demoAccountRegistrationRequest->TradingPlatformId = $config['tradingPlatforms']['DEMO']['id'];
        $demoAccountRegistrationRequest->GroupName = $groupName;
        $demoAccountRegistrationRequest->CountryId = $countryId;
        $demoAccountRegistrationRequest->FirstName = $firstName;
        $demoAccountRegistrationRequest->LastName = $lastName;
        $demoAccountRegistrationRequest->PhoneCountryCode = $phoneCountryCode;
        $demoAccountRegistrationRequest->PhoneAreaCode = $phoneAreaCode;
        $demoAccountRegistrationRequest->PhoneNumber = $phoneNumber;
        $demoAccountRegistrationRequest->Email = $email;
        $demoAccountRegistrationRequest->Password = $password;

        $demoAccountRegistrationRequest->LoggedInAccountId = '00000000-0000-0000-0000-000000000000';
        $demoAccountRegistrationRequest->PlaceOfBirth = '';

        $demoAccountRegistrationRequest->AdditionalInfo = new DynamicAttributeInfo();
        $demoAccountRegistrationRequest->EnvironmentInfo = new EnvironmentInfo();
        $demoAccountRegistrationRequest->MarketingInfo = new MarketingInfo();

        $registerDemoAccount = new RegisterDemoAccount();

        $registerDemoAccount->ownerUserId = $ownerUserId;
        $registerDemoAccount->organizationName = $config['organization'];
        $registerDemoAccount->businessUnitName = $config['businessUnitName'];
        $registerDemoAccount->demoAccountRegistrationRequest = $demoAccountRegistrationRequest;

        $leverateCrm = getCrm($config);

        $registerDemoAccountResponse = $leverateCrm->RegisterDemoAccount($registerDemoAccount);
        $result = $registerDemoAccountResponse->RegisterDemoAccountResult;

        $ResultInfo = $result->Result;

        $result = $result->AccountId;
        $success = $ResultInfo->Code;
        $message = $ResultInfo->Message;

    } catch (Exception $e) {
        echo  '<div style="color:#ffffff;">Caught exception: '.  $e. "\n</div>";
        echo '<pre>';
        echo "Request headers:\n";
        print_r($leverateCrm->soapClient->__getLastResponseHeaders());
        echo "Request Body:\n";
        print_r($leverateCrm->soapClient->__getLastRequest());
        echo "Response headers:\n";
        print_r($leverateCrm->soapClient->__getLastResponseHeaders());
        echo "Body:\n";
        print_r($leverateCrm->soapClient->__getLastResponse());
        echo '</pre>';
        exit;
    }

} else {
    $result = null;
    $success = 'Success';
    $message = null;
}
try {
    $gc = new GetCountries();
    $gc->businessUnitName = $config['businessUnitName'];
    $gc->organizationName = $config['organization'];
    $gc->ownerUserId = $config['ownerUserId'];

    $leverateCrm = getCrm($config);

    $CountriesResponse = new GetCountriesResponse();
    $CountriesResponse = $leverateCrm->GetCountries($gc);
    $countries = $CountriesResponse->GetCountriesResult;

    $gp = new GetTradingPlatformGroups();
    $gp->businessUnitName = $config['businessUnitName'];
    $gp->organizationName = $config['organization'];
    $gp->ownerUserId = $config['ownerUserId'];
    $gp->tradingPlatformId = $config['tradingPlatforms']['DEMO']['id'];

    $response = new GetTradingPlatformGroupsResponse();
    $response = $leverateCrm->GetTradingPlatformGroups($gp);

    $groups = $response->GetTradingPlatformGroupsResult;


} catch (Exception $e) {
    print  'Caught exception: '.  $e. "\n";
    exit;
}

view('RegisterRealAccount', array(
    'pageTitle' => 'Register new demo/real Account',
    'result' => $result,
    'isResultSuccess' => ($success=='Success'),
    'errorMessage' => $message,
    'countries' => $countries->CountryInfo,
    'tradingPlatformGroups' => $groups->string,
));
