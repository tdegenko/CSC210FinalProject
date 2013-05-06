<?php
require_once('AppInfo.php');
// Enforce https on production
if (substr(AppInfo::getUrl(), 0, 8) != 'https://' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
  header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  exit();
}

// This provides access to helper functions defined in 'utils.php'
require_once('utils.php');
require_once('sdk/src/facebook.php');

$facebook = new Facebook(array(
  'appId'  => AppInfo::appID(),
  'secret' => AppInfo::appSecret(),
  'sharedSession' => true,
  'trustForwarded' => true,
));

$access_token = $_SESSION['token'];
$facebook->setAccessToken($access_token);

$user_id = $facebook->getUser();
if ($user_id) {
   
  try {
    // Fetch the viewer's basic information
    $basic = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    // If the call fails we check if we still have a user. The user will be
    // cleared if the error is because of an invalid accesstoken
    if (!$facebook->getUser()) {
      header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
      exit();
    }
  }
}


  $e_id = 299873546813272;
  $e_details = $facebook -> api("/{$e_id}");
  //echo "$e_details";
  $att = $_REQUEST["attend"];

  //echo "$att";
  $data = $facebook -> api("/{$e_id}/invited", 'POST', array("users"=>implode(",", $att));
 
?>

  