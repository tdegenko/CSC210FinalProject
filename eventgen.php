<?php
require_once('AppInfo.php');
require_once('daycalutils.php');
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
  if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
  if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");
  if (!isset($_REQUEST["day"])) $_REQUEST["day"] = date("d");
  $month= $_REQUEST["month"];
  $year = $_REQUEST["year"];
  $day  = $_REQUEST["day"];
  $hour = $_REQUEST["hour"];
  $min  = $_REQUEST["min"];
  $flist= $_REQUEST["friends"];
   
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

?>


    <?php
      if ($user_id) {
    ?>
    <?php
    $access_token = $facebook->getAccessToken();
    $event_url = "https://graph.facebook.com/me/events?access_token=".$access_token;
    ?>
    <form enctype="multipart/form-data" action="<?php echo $event_url; ?>" method="post">
        <p><label for="name">Event Name</label><input type="text" name="name" value="" /></p>
        <p><label for="description">Event Description</label><textarea name="description"></textarea></p>
        <p><label for="location">Location</label><input type="text" name="location" value="" /></p>
        <p><label for="">Start Time</label><input type="text" name="start_time" value="<?php echo date(DATE_ISO8601); ?>" /></p>
        <p><label for="end_time">End Time</label><input type="text" name="end_time" value="<?php echo date(DATE_ISO8601, mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))); ?>" /></p>
        <p><label for="picture">Event Picture</label><input type="file" name="picture" /></p>
        <p>
            <label for="privacy_type">Privacy</label>
            <input type="radio" name="privacy_type" value="OPEN" checked='checked'/>Open&nbsp;&nbsp;&nbsp;
            <input type="radio" name="privacy_type" value="CLOSED" />Closed&nbsp;&nbsp;&nbsp;
            <input type="radio" name="privacy_type" value="SECRET" />Secret&nbsp;&nbsp;&nbsp;
        </p>
        <p><input type="submit" value="Create Event" /></p>
    </form>
    <?php
      }
    ?>
