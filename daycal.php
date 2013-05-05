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
  $cMonth = $_REQUEST["month"];
  $cYear = $_REQUEST["year"];
  $day = $_REQUEST["day"];
  $flist = $_REQUEST["friends"];
   
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
 $mevents = $facebook->api(array(
    'method' => 'fql.query',
    'query' => 'SELECT name,start_time,end_time FROM event WHERE eid IN(SELECT eid FROM event_member WHERE uid = me()) AND start_time >='.strtotime($cMonth."/1/".$cYear."T00:00:00").' AND start_time <='.strtotime($cMonth."/".$day."/".$cYear."T24:00:00").' ORDER BY start_time desc'
  ));
 if ($flist){
    $fqlflist="uid=$flist[0]";
    unset($flist[0]);
    var_dump($flist);
    foreach ($flist as $f){
        $fqlflist.=" OR uid=$f";
    }
    $fevents = $facebook->api(array(
        'method' => 'fql.query',
        'query' => 'SELECT start_time FROM event WHERE eid IN(SELECT eid FROM event_member WHERE '.$fqlflist.') AND start_time >='.strtotime($cMonth."/1/".$cYear."T00:00:00").' AND start_time <='.strtotime($cMonth."/1/".$cYear."T00:00:00").' ORDER BY start_time desc'
    ));
 }

}

?>
<meta name="cday" content="<?=$day?>"/>
<link href="daycal.css" type="text/css" rel="stylesheet" />
<div class="wrap">
<?php
foreach($mevents as $event){
    $name=idx($event,"name");
    $stime=idx($event,"start_time");
    $etime=idx($event,"end_time");
?>
<div class="busyh" style="top:<?=genLoc(he($stime))?>px; height:<?=genH($stime,$etime)?>px">
    <?=he($name);?>
</div>
<?php
}
foreach($fevents as $event){
    $name=idx($event,"name");
    $stime=idx($event,"start_time");
    $etime=idx($event,"end_time");
?>
<div class="busy" style="top:<?=genLoc(he($stime))?>;height:<?=genH($stime,$etime)?>">
    <?=he($name);?>
</div>
<?php
}
?>
<table class="mainDay">

<?php
for($i=0;$i<24;$i++){
?>
    <tr class="hour">
        <td>
            <?=$i?>
        </td>
    </tr>
<?php
}
?>
</table>
</div>
