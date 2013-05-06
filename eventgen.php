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
  if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
  if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");
  if (!isset($_REQUEST["day"])) $_REQUEST["day"] = date("d");
  if (!isset($_REQUEST["hour"])) $_REQUEST["hour"] = date("G");
  if (!isset($_REQUEST["min"])) $_REQUEST["min"] = date("i");
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

$friends = idx($facebook->api('/me/friends?limit=1000'), 'data', array());

$app_using_friends = $facebook->api(array(
    'method' => 'fql.query',
    'query' => 'SELECT uid, name FROM user WHERE uid IN(SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1'
  ));

?>


    <?php
      if ($user_id) {
    ?>
    <?php
    $access_token = $facebook->getAccessToken();
    $event_url = "https://graph.facebook.com/me/events?access_token=".$access_token;
    ?>
    <script src="javascript/utils.js"></script>
    <script>
            $( "#datepicker" ).datepicker();
            $( "#datepicker" ).datepicker("setDate",<?=$month."/".$day."/".$year?>);
    </script>
    <form id="eventform" enctype="multipart/form-data" action="<?php echo $event_url; ?>" method="post">
        <p><label for="name">Event Name</label><input type="text" name="name" value="" /></p>
        <p><label for="description">Event Description</label><textarea name="description"></textarea></p>
        <p><label for="location">Location</label><input type="text" name="location" value="" /></p>
        <p>Date: <input type="text" id="datepicker" />
        </p>
        <p>
        Start Time: <select name="shour">
        <?php 
            for($i=0;$i<24;$i++){
        ?>
            <option value="<?=$i?>" <?php if($i==$hour){echo('selected="selected"');}?>><?=$i?>h</option>
        <?php
        }
        ?>
        </select>
        <select name="smin">
        <?php 
            for($i=0;$i<60;$i++){
        ?>
            <option value="<?=$i?>" <?php if($i==$min){echo('selected="selected"');}?>><?=$i?>m</option>
        <?php
        }
        ?>
        </select>
        </p>
        <p>
        End Time: <select name="ehour">
        <?php 
            for($i=0;$i<24;$i++){
        ?>
            <option value="<?=$i?>" <?php if($i==$hour+1){echo('selected="selected"');}?>><?=$i?>h</option>
        <?php
        }
        ?>
        </select>
        <select name="emin">
        <?php 
            for($i=0;$i<60;$i++){
        ?>
            <option value="<?=$i?>" <?php if($i==$min){echo('selected="selected"');}?>><?=$i?>m</option>
        <?php
        }
        ?>
        </select>
        <p>
            <label for="privacy_type">Privacy</label>
            <input type="radio" name="privacy_type" value="OPEN" checked='checked'/>Open&nbsp;&nbsp;&nbsp;
            <input type="radio" name="privacy_type" value="CLOSED" />Closed&nbsp;&nbsp;&nbsp;
            <input type="radio" name="privacy_type" value="SECRET" />Secret&nbsp;&nbsp;&nbsp;
        </p>
        <p><input type="submit" value="Create Event" /></p>
    </form>

    <div>
	
		<div class="list">
	        <h3>Friends using this app</h3>
	        
	         <form name = "invite" action = "invite.php"  method = "post">
	          	<?php
	                $w = 0;
		            foreach ($friends as $friend) {
		              // Extract the pieces of info we need from the requests above
		              $id = idx($friend, 'id');
		              $name = idx($friend, 'name');
		              if ($w % 5== 0){
			          echo "<br>";
			          
		}
		          ?>
	            <input type = "checkbox" name = "attend[]" value = "<?php echo he($id); ?>">
	            <a href="https://www.facebook.com/<?php echo he($id); ?>" target="_top">
	              <img src="https://graph.facebook.com/<?php echo he($id); ?>/picture?type=square" alt="<?php echo he($name); ?>">
	              <?php echo he($name); ?>
	            </a>
	        
	          
	          <?php
	            $w++;
	            }
	          
	          ?>
			<input type="submit" value="Invite" /></p>
			</form>
	        
	      </div>
	
	
	
	
<!--    ==================OLD VERSION==================
    </div>
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
-->
