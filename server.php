<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

//Get current date and time
date_default_timezone_set('Europe/Berlin');
$d = date("d-m-Y");
//echo " Date:".$d."<BR>";
$t = date("H:i:s");
// connect to the database

$db = mysqli_connect('localhost', 'id8537817_espdemo123', 'slaven123', 'id8537817_espdemo');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username= ?  OR email= ? LIMIT 1";
  
  $stmt1 = mysqli_prepare($db, $user_check_query);
  
	if ($stmt1 === false) {
		error_log(mysqli_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_bind_param($stmt1, "ss", $username, $email);
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
  
  //$result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result1);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query_insert_user = "INSERT INTO users (username, email, password) 
  			  VALUES(?, ?, ?)";
	
	$stmt2 = mysqli_prepare($db, $query_insert_user);
	
	if ($stmt2 === false) {
		error_log(mysqli_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_bind_param($stmt2, "sss", $username, $email, $password);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_execute($stmt2);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}		  
			  

  	//$_SESSION['username'] = $username;
  	//$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}
// ... 

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query_login_user = "SELECT * FROM users WHERE username= ? AND password= ?";
  	//$results = mysqli_query($db, $query);
	
	$stmt3 = mysqli_prepare($db, $query_login_user);
	
	if ($stmt3 === false) {
		error_log(mysqli_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_bind_param($stmt3, "ss", $username, $password);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_execute($stmt3);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}		  
	
	$result2 = $stmt3->get_result();
	
  	if (mysqli_num_rows($result2) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}
mysqli_close($db);
?>