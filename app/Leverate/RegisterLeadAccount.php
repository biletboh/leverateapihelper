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

    try {
        $countryId = new guid();
        $countryId = $country;

        $ownerUserId = new guid();
        $ownerUserId = $config['ownerUserId'];

        $leadAccountRegistrationRequest = new LeadAccountRegistrationRequest();
        $leadAccountRegistrationRequest->CountryOfCitizenshipId = $countryId;
        $leadAccountRegistrationRequest->CountryId = $countryId;
        $leadAccountRegistrationRequest->FirstName = $firstName;
        $leadAccountRegistrationRequest->LastName = $lastName;
        $leadAccountRegistrationRequest->Email = $email;
        $leadAccountRegistrationRequest->PhoneAreaCode = $phoneAreaCode;
        $leadAccountRegistrationRequest->PhoneCountryCode = $phoneCountryCode;
        $leadAccountRegistrationRequest->PhoneNumber = $phoneNumber;

        $leadAccountRegistrationRequest->AdditionalInfo = new DynamicAttributeInfo();
        $leadAccountRegistrationRequest->EnvironmentInfo = new EnvironmentInfo();
        $leadAccountRegistrationRequest->MarketingInfo = new MarketingInfo();


        $registerLeadAccount = new RegisterLeadAccount();

        $registerLeadAccount->ownerUserId = $ownerUserId;
        $registerLeadAccount->organizationName = $config['organization'];
        $registerLeadAccount->businessUnitName = $config['businessUnitName'];
        $registerLeadAccount->leadAccountRegistrationRequest = $leadAccountRegistrationRequest;

        $leverateCrm = getCrm($config);

        $registerLeadAccountResponse = $leverateCrm->RegisterLeadAccount($registerLeadAccount);
        $accountRegistrationResult = $registerLeadAccountResponse->RegisterLeadAccountResult;

        $ResultInfo = new ResultInfo();
        $ResultInfo = $accountRegistrationResult->Result;

        $result = $accountRegistrationResult->AccountId;
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
try {
    $gc = new GetCountries();
    $gc->businessUnitName = $config['businessUnitName'];
    $gc->organizationName = $config['organization'];
    $gc->ownerUserId = $config['ownerUserId'];

    $leverateCrm = getCrm($config);

    $CountriesResponse = new GetCountriesResponse();
    $CountriesResponse = $leverateCrm->GetCountries($gc);

    $countries = $CountriesResponse->GetCountriesResult;

} catch (Exception $e) {
    print  'Caught exception: '.  $e. "\n";
    exit;
}

view('RegisterLeadAccount', array(
    'pageTitle' => 'Register Lead Account',
    'result' => $result,
    'isResultSuccess' => $success,
    'errorMessage' => $message,
    'countries' => $countries->CountryInfo,
));
