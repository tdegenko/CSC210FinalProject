<?php
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
?>