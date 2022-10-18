<?php
session_start();

//$servername = "localhost";
//$username = "id8537817_espdemo123";
//$password = "slaven123";
//$dbname = "id8537817_espdemo";

// Create connection
$db = mysqli_connect('localhost', 'id8537817_espdemo123', 'slaven123', 'id8537817_espdemo');
// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
} 

print_r($_POST);

$device_name = isset($_POST['name']) ? $_POST['name'] : null;

$query_user = "UPDATE devices SET status='ON' WHERE device = ?";// dodat i da checka user id

$stmt = mysqli_prepare($db, $query_user);
if ($stmt === false) {
    error_log(mysqli_error($db));
    die("Sorry, there has been a software error");
}
$ok = mysqli_stmt_bind_param($stmt, "s", $device_name);//doadt i user id
if ($ok === false) {
    error_log(mysqli_stmt_error($db));
    die("Sorry, there has been a software error");
}
$ok = mysqli_stmt_execute($stmt);
if ($ok === false) {
    error_log(mysqli_stmt_error($db));
    die("Sorry, there has been a software error");
}

$file_name = $device_name.".txt";

if(!file_exists($file_name))
{
	$myfile = fopen($file_name, "w") or die("Unable to open file!");
	fclose($myfile);
}

mysqli_close($db);
?>