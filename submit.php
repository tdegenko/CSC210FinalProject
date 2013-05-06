<?php




$url=parse_url(getenv("CLEARDB_DATABASE_URL"));

    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"],1);
            


$con = mysql_connect($server, $username, $password);


if (!$con){
	die ("Unable to connect db");
}
$select = mysql_select_db($db);
if (!$select){
	die ("Unable to connect database");
}

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


//Don't know how id is represented
$id = $user_id;
$first = $_POST["first"];
$second = $_POST["second"];
$third = $_POST["third"];
$query = "SELECT * FROM test1 p WHERE p.uid = $id";
$results = mysql_query($query);



if (mysql_num_rows($results) == "")
{

	$sql1 = "INSERT INTO test1
	VALUES
	('$id', '$first', '$second', '$third')";
	mysql_query($sql1);
}	

else
{

	$sql2 = sprintf("UPDATE test1 SET first_pref = '%s', second_pref = '%s', third_pref = '%s' WHERE uid = '%d'", $first, $second, $third, $id);
	mysql_query($sql2);
//	if (!mysql_query($con, $sql2))
//	{
//		die('Error: ' . mysqli_error($con));
//	}
}


echo "Preferences saved";

//mysql_close($con);
?>
