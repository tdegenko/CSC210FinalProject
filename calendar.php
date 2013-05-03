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

$user_id = $facebook->getUser();
if ($user_id) {
  try {
    // Fetch the viewer's basic information
    $basic = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    // If the call fails we check if we still have a user. The user will be
    // cleared if the error is because of an invalid accesstoken
    if (!$facebook->getUser()) {
//      header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
//      exit();
    }
  }
// $events = $facebook->api(array(
//    'method' => 'fql.query',
//    'query' => 'SELECT name, pic, start_time, end_time, location, description FROM event WHERE eid IN(SELECT eid FROM event_member WHERE uid = me()) AND start_time >= now() ORDER BY start_time desc'
//  ));

}

?>
<?php
$monthNames = Array("January", "February", "March", "April", "May", "June", "July", 
"August", "September", "October", "November", "December");

if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");

$cMonth = $_REQUEST["month"];
$cYear = $_REQUEST["year"];
 
$prev_year = $cYear;
$next_year = $cYear;
$prev_month = $cMonth-1;
$next_month = $cMonth+1;
 
if ($prev_month == 0 ) {
    $prev_month = 12;
    $prev_year = $cYear - 1;
}
if ($next_month == 13 ) {
    $next_month = 1;
    $next_year = $cYear + 1;
}
?>
<meta name="nyear" content="<?=$next_year?>"/>
<meta name="nmonth" content="<?=$next_month?>"/>
<meta name="pyear" content="<?=$prev_year?>"/>
<meta name="pmonth" content="<?=$prev_month?>"/>
<link href="calendar.css" type="text/css" rel="stylesheet" />
<div id="tables">
    <table width="100%">

        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td colspan="7" class="title"><?php echo $monthNames[$cMonth-1].' '.$cYear; ?></td>
                    </tr>
                    <tr>
                        <td class="title">Sunday</td>
                        <td class="title">Monday</td>
                        <td class="title">Tuesday</td>
                        <td class="title">Wednesday</td>
                        <td class="title">Thursday</td>
                        <td class="title">Friday</td>
                        <td class="title">Saturday</td>
                    </tr>
<?php 
$timestamp = mktime(0,0,0,$cMonth,1,$cYear);
$maxday = date("t",$timestamp);
$thismonth = getdate ($timestamp);
$startday = $thismonth['wday'];
for ($i=0; $i<($maxday+$startday); $i++) {
    if(($i % 7) == 0 ) echo "<tr>";
    if($i < $startday) echo "<td></td>";
    else echo "<td class=\"day\">". "<a href='date.php?day=$i&month=$cMonth&year=$cYear'>". ($i - $startday + 1)."</a>". "</td>";
    if(($i % 7) == 6 ) echo "</tr>";
}
?>

                </table>
            </td>
        </tr>
    </table>
</div>