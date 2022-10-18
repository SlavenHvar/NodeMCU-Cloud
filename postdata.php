<?php
    $db = mysqli_connect('localhost', 'id8537817_espdemo123', 'slaven123', 'id8537817_espdemo');
	$powerloss_db="";
	$Email="";
	$Powerloss="NO";
	//if ($db->connect_error) {
	//	die("Connection failed: " . $db->connect_error);
	//} 

    $data = isset($_POST['data']) ? $_POST['data'] : null;
    $device_name = isset($_POST['name']) ? $_POST['name'] : null;
	
	date_default_timezone_set('Europe/Berlin');
	
    $date = date('Y-m-d H:i:s');
	
	$query_devices="SELECT Email,Powerloss FROM devices WHERE device=?";

	$stmt = mysqli_prepare($db, $query_devices);
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
	
	$result = $stmt->get_result();
	
	if ($result->num_rows > 0) 
	{
		// output data of each row
		while($row = $result->fetch_assoc()) 
		{
			$powerloss_db = $row["Powerloss"];
			$Email = $row["Email"];
		}
	}
	if($powerloss_db =="YES")
	{
		$query_update = "UPDATE devices SET data=?, _datetime = ?, Powerloss=? WHERE device = ?";// dodat i da checka user id

		$stmt2 = mysqli_prepare($db, $query_update);
		if ($stmt2 === false) {
		error_log(mysqli_error($db));
		die("Sorry, there has been a software error");
		}
		$ok = mysqli_stmt_bind_param($stmt2, "ssss", $data, $date, $Powerloss, $device_name);//doadt i user id
		if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
		}
		$ok = mysqli_stmt_execute($stmt2);
		if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
		}
		$to      = $Email;
		$subject = $device_name." is turned ON!";
		$message = $device_name." is turned ON after powerloss";
		$headers = 'From: hvarIOT' . "\r\n" .
'Reply-To: slaventojic@gmail.com.com' . "\r\n" .
'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
	}
	else
	{
		$query_update = "UPDATE devices SET data=?, _datetime = ? WHERE device = ?";// dodat i da checka user id

		$stmt2 = mysqli_prepare($db, $query_update);
		if ($stmt2 === false) {
		error_log(mysqli_error($db));
		die("Sorry, there has been a software error");
		}
		$ok = mysqli_stmt_bind_param($stmt2, "sss", $data, $date, $device_name);//doadt i user id
		if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
		}
		$ok = mysqli_stmt_execute($stmt2);
		if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
		}
	}
	
	
	

    mysqli_close($db);
?>