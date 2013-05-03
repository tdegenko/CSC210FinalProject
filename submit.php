<?php


$con = mysql_connect("localhost", "root", "1989413");


if (!$con){
	die ("Unable to connect db");
}
$select = mysql_select_db("test1");
if (!$select){
	die ("Unable to connect test1");
}



//Don't know how id is represented
$id = 4;
$first = $_POST["first"];
$second = $_POST["second"];
$third = $_POST["third"];
$query = "SELECT * FROM example1 p WHERE p.uid = $id";
$results = mysql_query($query);



if (mysql_num_rows($results) == "")
{
	echo "noresults";
	$sql1 = "INSERT INTO example1
	VALUES
	('$id', '$first', '$second', '$third')";
	mysql_query($sql1);
}	

else
{
	echo "111";
	$sql2 = sprintf("UPDATE example1 SET first_pref = '%s', second_pref = '%s', third_pref = '%s' WHERE uid = '%d'", $first, $second, $third, $id);
	mysql_query($sql2);
//	if (!mysql_query($con, $sql2))
//	{
//		die('Error: ' . mysqli_error($con));
//	}
}


echo "Preferences saved";

//mysql_close($con);
?>