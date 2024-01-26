<?php

session_start();
// added in v4.0.0
require_once 'autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

// init app with app id and secret


FacebookSession::setDefaultApplication( '1917640701854957','361b8ac055e547abc9cc6eca08afc42f' );
$helper = new FacebookRedirectLoginHelper('https://job-board-portal-script.logicspice.com/app/webroot/facebook/fbconfig.php');
try {
    $session = $helper->getSessionFromRedirect();
} catch (FacebookRequestException $ex) {

    // When Facebook returns an error
} catch (Exception $ex) {
    // When validation fails or other local issues
}
// see if we have a session


if (isset($session)) {

    //print_r($session);exit;
    // graph api request for user data
    $request = new FacebookRequest($session, 'GET', '/me?locale=en_US&fields=id,name,email,first_name,last_name');
    $response = $request->execute();
    // get response
    $graphObject = $response->getGraphObject();
    $fbid = $graphObject->getProperty('id');              // To Get Facebook ID
    $fbfullname = $graphObject->getProperty('name');
    $femail = $graphObject->getProperty('email'); // To Get Facebook full name
    // $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
    $first_name = $graphObject->getProperty('first_name');    // To Get Facebook email ID
    $last_name = $graphObject->getProperty('last_name');    // To Get Facebook email ID
    /* ---- Session Variables ----- */
    $_SESSION['FB']['fbid'] = $fbid;
    $_SESSION['FB']['fullname'] = $fbfullname;
    $_SESSION['FB']['email'] = $femail;
    $_SESSION['FB']['first_name'] = $first_name;
    $_SESSION['FB']['last_name'] = $last_name;

    header("Location: https://job-board-portal-script.logicspice.com/users/fblogin/");
    exit;
} else {
    $loginUrl = $helper->getLoginUrl();
    header("Location: " . $loginUrl);
}
?>