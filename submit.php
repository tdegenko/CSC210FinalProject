<?php
<<<<<<< HEAD


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
=======
$con = mysql_connect("localhost", "user", "password", "db");

if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//Don't know how id is represented
$id = ;
$first = $_POST[first];
%second = $_POST[second];
$third = $_POST[third];
$query = sprintf("SELECT * FROM preferences p WHERE p.id = '%s'", mysql_real_escape_string($id));
$results = mysql_query($query);

if ($results == null)
{
	$sql = "INSERT INTO preferences (id, first, second, third)
	VALUES
	($id, $first, $second, $third)";
}
else
{
	$sql = "UPDATE preferences SET first = $first AND second = $second AND third = $third WHERE id = $id");
}

if (!mysqli_query($con, $sql))
{
	die('Error: ' . mysqli_error($con));
}
echo "Preferences saved";

mysqli_close($con);
>>>>>>> 873f444bc15924d06b1370721e0907cc74e26693
?>