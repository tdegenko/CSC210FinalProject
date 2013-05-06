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



//Don't know how id is represented
$id = 1;
$first = $_POST["first"];
$second = $_POST["second"];
$third = $_POST["third"];
$query = "SELECT * FROM test1 p WHERE p.uid = $id";
$results = mysql_query($query);

echo "$first";

if (mysql_num_rows($results) == "")
{
	echo "noresults";
	$sql1 = "INSERT INTO test1
	VALUES
	('$id', '$first', '$second', '$third')";
	mysql_query($sql1);
}	

else
{
	echo "111";
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
