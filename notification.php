<?php
// compare dates and if device hasn't send adata for 5 minutes send email, notifiy db that email is sent(YES)(then don't send again email), and when the diff between the 
//device is smaller then five again (the device is back online), send notification to db(NO).
$powerloss="YES";

$db = mysqli_connect('localhost', 'id8537817_espdemo123', 'slaven123', 'id8537817_espdemo');

$query_devices="SELECT * FROM devices";

$stmt = mysqli_prepare($db, $query_devices);
if ($stmt === false) {
	error_log(mysqli_error($db));
	die("Sorry, there has been a software error");
	
}

$ok = mysqli_stmt_execute($stmt);
if ($ok === false) {
	error_log(mysqli_stmt_error($db));
	die("Sorry, there has been a software error");
	
}

$result_devices = $stmt->get_result();
	
   
//$date1 = new DateTime("now");

date_default_timezone_set('Europe/Berlin');
$date_now = new DateTime(date('Y-m-d H:i:s'));

if ($result_devices->num_rows > 0) 
{
	// output data of each row
	while($row = $result_devices->fetch_assoc()) 
	{
		$interval = $date_now ->diff(new DateTime($row["_datetime"]));
		//echo $interval->format('%R%a days');
		//echo $interval->i.' minutes<br>';
		$result = intval($interval->i);
		
		echo $result."<br/>";
		
		if($result > 10 && $row["Powerloss"]=="NO")
		{
			$to      = $row["Email"];
			$subject = 'Powerloss'.' occurred to device '.$row["device"];
			$message = 'A powerloss occurred to your '.$row["device"];
			$headers = 'From: hvarIOT' . "\r\n" .
    'Reply-To: slaventojic@gmail.com.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

			mail($to, $subject, $message, $headers);
			
			//set powerloss to YES
			$query_update = "UPDATE devices SET PowerLoss=? WHERE device = ?";
			
		    $stmt = mysqli_prepare($db, $query_update);
			if ($stmt === false) {
			error_log(mysqli_error($db));
			die("Sorry, there has been a software error");
			
			}
			$ok = mysqli_stmt_bind_param($stmt, "ss",$powerloss ,$row["device"]);//doadt i user id
			if ($ok === false) {
			error_log(mysqli_stmt_error($db));
			die("Sorry, there has been a software error");
			
			}
			$ok = mysqli_stmt_execute($stmt);
			if ($ok === false) {
			error_log(mysqli_stmt_error($db));
			die("Sorry, there has been a software error");
			
			}
		}

	}
} 

mysqli_close($db);


?>