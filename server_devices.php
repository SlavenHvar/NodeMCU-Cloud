<?php
session_start();

// initializing variables
$device = "";
$description="";
$powerloss="NO";
$errors = array();



// connect to the database

$db = mysqli_connect('localhost', 'id8537817_espdemo123', 'slaven123', 'id8537817_espdemo');



// REGISTER device
if (isset($_POST['reg_user'])) {
	
  // receive all input values from the form
  $device = mysqli_real_escape_string($db, $_POST['device']);
  $description = mysqli_real_escape_string($db, $_POST['description']);
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $deviceType = mysqli_real_escape_string($db, $_POST['deviceType']);
  $status = "OFF";//set initial ststus to OFF
	
	

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($device)) { array_push($errors, "Device name is required!"); }
  if (empty($description)) { array_push($errors, "Description is required!"); }
  if (empty($username)) { array_push($errors, "Username is required!"); }
  if (empty($deviceType)) { array_push($errors, "Device type is required!"); }
  // add for description


  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $device_check_query = "SELECT * FROM devices WHERE device= ? LIMIT 1";//!!!!!!!!!!!!!!!!!make paramerized query
  
  $stmt1 = mysqli_prepare($db, $device_check_query);
  
	if ($stmt1 === false) {
		error_log(mysqli_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_bind_param($stmt1, "s", $device);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_execute($stmt1);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}
	
  $result1 = $stmt1->get_result();
  
  //$result = mysqli_query($db, $device_check_query);
  $user = mysqli_fetch_assoc($result1);
  
  if ($user) { // if user exists
    if ($user['device'] === $device) {
      array_push($errors, "device already exists");
    }

    
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	
	$query_user="SELECT id,email FROM users WHERE username = ?";//!!!!!!!!!!!!!!!!!make paramerized query

	//$users_id = 1;
	
	$stmt2 = mysqli_prepare($db, $query_user);
  
	if ($stmt2 === false) {
		error_log(mysqli_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_bind_param($stmt2, "s", $username);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_execute($stmt2);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}
	
    $result2 = $stmt2->get_result();
	
	//$result = mysqli_query($db, $query_user);
	
/* 	echo '<script language="javascript">';
	echo 'alert("'.$_SESSION['username'].'")';
	echo '</script>'; */
	
	
	
	
	if ($result2->num_rows > 0) 
	{
		// output data of each row
		while($row = $result2->fetch_assoc()) 
		{
			$users_id = $row["id"];
			$email = $row["email"];
		}
	} 
		
		
		
  	$query_insert_device = "INSERT INTO devices (device, status, users_id, username, description, Email, PowerLoss, deviceType ) 
  			  VALUES( ?, ?, ?, ?, ?, ?, ?, ?)";
			
	$stmt3 = mysqli_prepare($db, $query_insert_device);

	if ($stmt3 === false) {
		//error_log(mysqli_error($db));
		//die("Sorry, there has been a software error");
		//echo "one";
		//echo $device.";".$status.";".$users_id.";".$username.";".$description;
	}
	$ok = mysqli_stmt_bind_param($stmt3, "ssssssss", $device, $status, $users_id ,$username, $description, $email, $powerloss, $deviceType );
	if ($ok === false) {
		//error_log(mysqli_stmt_error($db));
		//die("Sorry, there has been a software error");
		//echo "two";
		//echo $device.";".$status.";".$users_id.";".$username.";".$description;
	}
	$ok = mysqli_stmt_execute($stmt3);
	if ($ok === false) {
		//error_log(mysqli_stmt_error($db));
		//die("Sorry, there has been a software error");
		//echo "three";
		//echo $device.";".$status.";".$users_id.";".$username.";".$description;
	}

			
  	//mysqli_query($db, $query);
  	$_SESSION['device'] = $device;
  	$_SESSION['success'] = "device added!";
  	header('location: index.php');
  }
}

mysqli_close($db);
?>