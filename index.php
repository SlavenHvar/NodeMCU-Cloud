<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>

<!-- logged in user information -->
<?php  if (isset($_SESSION['username'])) :?>	
	<?php if($_SESSION['username']=="slaven") : ?>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
				  <p style="color: white;">Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
				</div>
				<ul class="nav navbar-nav navbar-right">
				  <li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Add new user </a></li>
				  <li><a href="register_device.php"><span class="glyphicon glyphicon-plus-sign"></span> Add new device </a></li>
				  <li><a href="index.php?logout='1'"><span class="glyphicon glyphicon-log-out"></span> Logout </a></li>
				</ul>
			</div>
			</nav>
	<?php else : ?>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
				  <p style="color: white;">Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
				</div>
				<ul class="nav navbar-nav navbar-right">
				  <li><a href="index.php?logout='1'"><span class="glyphicon glyphicon-log-out"></span> Logout </a></li>
				</ul>
			</div>
			</nav>
	<?php endif; ?>
<?php endif ?>

<?php 
    $db = mysqli_connect('localhost', 'id8537817_espdemo123', 'slaven123', 'id8537817_espdemo');
	
	$query_user_id="SELECT id FROM users WHERE username = ?";

	$stmt1 = mysqli_prepare($db, $query_user_id);
	if ($stmt1 === false) {
		error_log(mysqli_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_bind_param($stmt1, "s", $_SESSION['username']);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_execute($stmt1);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}
	
	$result_user_id = $stmt1->get_result();
	
	if ($result_user_id->num_rows > 0) 
	{
		// output data of each row
		while($row = $result_user_id->fetch_assoc()) 
		{
			$user_id = $row["id"];
		}
	} 
	
	$query_devices="SELECT * FROM devices WHERE users_id = ? AND username= ?";//zbog sigurnsonih razloga ostavit da se ispitije id i username?

	$stmt2 = mysqli_prepare($db, $query_devices);
	if ($stmt2 === false) {
		error_log(mysqli_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_bind_param($stmt2, "ss", $user_id, $_SESSION['username']);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}
	$ok = mysqli_stmt_execute($stmt2);
	if ($ok === false) {
		error_log(mysqli_stmt_error($db));
		die("Sorry, there has been a software error");
	}
	
	$result_devices = $stmt2->get_result();

    //echo "<script>alert('device list loading')</script>";
	
    $devices = array();
	$devices_return_stat=array();
    $devices_return_datetime=array();
	$devices_return_description=array();
	$devices_return_devicetype=array();
	$devices_return_data=array();
	
	if ($result_devices->num_rows > 0) 
	{
		// output data of each row
		while($row = $result_devices->fetch_assoc()) 
		{
		   array_push($devices,$row["device"]);
		   array_push($devices_return_stat,$row["return_status"]);
		   array_push($devices_return_datetime,$row["_datetime"]);
		   array_push($devices_return_description,$row["description"]);
		   array_push($devices_return_devicetype,$row["deviceType"]);
		   array_push($devices_return_data,$row["data"]);
		}
	} 
	
	mysqli_close($db);
?> 

<html> 
<head> 
<title>NODEMCU CLOUD</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="indexStyle.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head> 
<body> 

  
<script type="text/javascript"> 

	

	var device_names = <?php echo json_encode($devices) ?>;
	
	var devices_return_stat = <?php echo json_encode($devices_return_stat) ?>;
	
	var devices_return_dt = <?php echo json_encode($devices_return_datetime) ?>;
	
	var devices_return_desc = <?php echo json_encode($devices_return_description) ?>;
	
	var devices_return_devtype = <?php echo json_encode($devices_return_devicetype) ?>;
	
	var devices_return_data = <?php echo json_encode($devices_return_data) ?>;
	
    var body = document.getElementsByTagName("body")[0];
	
	var btnArray = new Array(device_names.length);
	

    for (i = 0; i < device_names.length; i++) 
	{
		//alert(device_names[i]);
		
		if(devices_return_devtype[i] == "SWITCH")
		{
		    // 1. Create the button
			btnArray[i] = document.createElement("button");
			//button.innerHTML = '<p>'+device_names[i]+'</p></br>'+'<p>Device status: '+ devices_return_stat[i]+ "</br> at "+devices_return_dt[i]+'</p></br>';
			btnArray[i].value = device_names[i];// mora ostat koristi se kod kreiranja text file koji uređaj provjerava// kod posta on i off php file koristi se value (val)
			
			var deviceDescPara = document.createElement("h1");
			var nodeDescPara = document.createTextNode(devices_return_desc[i]);
			deviceDescPara.appendChild(nodeDescPara);
			
			var deviceNamePara = document.createElement("p");
			var nodeNamePara = document.createTextNode(device_names[i]);
			deviceNamePara.appendChild(nodeNamePara);
			
			var deviceStat = document.createElement("p");
			//var nodeStat = document.createTextNode("Device status: " + devices_return_stat[i] + " at " + devices_return_dt[i]);
			var nodeStat = document.createTextNode("Last online at : " + devices_return_dt[i]);
			deviceStat.appendChild(nodeStat);
			
			// 2. Append somewhere
			if(devices_return_stat[i]=="ON")// ostavit event listenere buttona unutar if else jer kad ga stavin vani zbog reference dolazi do greške
			{
				btnArray[i].classList.add("buttonIsOn");
				btnArray[i].innerHTML= '<span>ON</span>';
				
				btnArray[i].addEventListener ("click", function() {
			  //alert("did something");
					// Fire off the request to /form.php
					request = $.ajax({
						url: "off.php",
						type: "post",
						data: {name: $(this).val()}
					});

					// Callback handler that will be called on success
					request.done(function (response, textStatus, jqXHR){
						// Log a message to the console
						console.log("Hooray, it worked!");
						console.log(response);
						console.log(textStatus);
						setTimeout(function() { alert('Device status changed. Please click OK and wait a few seconds for device response. If the device status has not changed after 15 seconds please click refresh.'); }, 1);
						setTimeout(location.reload.bind(location), 10000);
					});

					// Callback handler that will be called on failure
					request.fail(function (jqXHR, textStatus, errorThrown){
						// Log the error to the console
						console.error(
							"The following error occurred: "+
							textStatus, errorThrown
						);
					});
			});
			}
			else if(devices_return_stat[i]=="OFF")
			{
				btnArray[i].classList.add("buttonIsOff");
				btnArray[i].innerHTML= '<span>OFF</span>';
				
				btnArray[i].addEventListener ("click", function() {
			  //alert("did something");
					// Fire off the request to /form.php
					request = $.ajax({
						url: "on.php",
						type: "post",
						data: {name: $(this).val()}
					});

					// Callback handler that will be called on success
					request.done(function (response, textStatus, jqXHR){
						// Log a message to the console
						console.log("Hooray, it worked!");
						console.log(response);
						console.log(textStatus);
						setTimeout(function() { alert('Device status changed. Please click OK and wait a few seconds for device response. If the device status has not changed after 15 seconds please click refresh.'); }, 1);
						setTimeout(location.reload.bind(location), 10000);
					});

					// Callback handler that will be called on failure
					request.fail(function (jqXHR, textStatus, errorThrown){
						// Log the error to the console
						console.error(
							"The following error occurred: "+
							textStatus, errorThrown
						);
					});
			});
			}
			else
			{
				btnArray[i].classList.add("buttonIsNull");
				btnArray[i].innerHTML= '<span>NULL</span>';
				
				btnArray[i].addEventListener ("click", function() {
			  //alert("did something");
					// Fire off the request to /form.php
					request = $.ajax({
						url: "on.php",
						type: "post",
						data: {name: $(this).val()}
					});

					// Callback handler that will be called on success
					request.done(function (response, textStatus, jqXHR){
						// Log a message to the console
						console.log("Hooray, it worked!");
						console.log(response);
						console.log(textStatus);
						setTimeout(function() { alert('Device status changed. Please click OK and wait a few seconds for device response. If the device status has not changed after 15 seconds please click refresh.'); }, 1);
						setTimeout(location.reload.bind(location), 10000);
					});

					// Callback handler that will be called on failure
					request.fail(function (jqXHR, textStatus, errorThrown){
						// Log the error to the console
						console.error(
							"The following error occurred: "+
							textStatus, errorThrown
						);
					});
			});
			}
			body.appendChild(deviceDescPara);
			body.appendChild(deviceNamePara);
			body.appendChild(btnArray[i]);
			body.appendChild(deviceStat);
		}
		else if (devices_return_devtype[i] == "SENSOR")
		{
			// 1. Create the button
			btnArray[i] = document.createElement("p");
			btnArray[i].value = device_names[i];// mora ostat koristi se kod kreiranja text file koji uređaj provjerava
			btnArray[i].innerHTML = devices_return_data[i];//'<p>'+devices_return_data[i]+'</p></br>'+'<p>Device status: '+ devices_return_stat[i]+ "</br> at "+devices_return_dt[i]+'</p></br>';
			btnArray[i].classList.add("SensorData");
			
			var deviceDescPara = document.createElement("h1");
			var nodeDescPara = document.createTextNode(devices_return_desc[i]);
			deviceDescPara.appendChild(nodeDescPara);
			
			var deviceNamePara = document.createElement("p");
			var nodeNamePara = document.createTextNode(device_names[i]);
			deviceNamePara.appendChild(nodeNamePara);
			
			var deviceStat = document.createElement("p");
			//var nodeStat = document.createTextNode("Device status: " + devices_return_stat[i] + " at " + devices_return_dt[i]);
			var nodeStat = document.createTextNode("Last online at : " + devices_return_dt[i]);
			deviceStat.appendChild(nodeStat);
			
			body.appendChild(deviceDescPara);
			body.appendChild(deviceNamePara);
			body.appendChild(btnArray[i]);
			body.appendChild(deviceStat);
			
			
		}
		
		
		
		
		
		
		
	
	}
	
	var btnRefresh = document.createElement("button");
		btnRefresh.innerHTML = '<span>Refresh</span>';
		btnRefresh.classList.add("buttonIsRefresh");
		body.appendChild(btnRefresh);
		
		btnRefresh.addEventListener ("click", function() {
				   setTimeout(location.reload.bind(location), 1);
				});
	

</script>   
  
<div class="container">
  <p>@HvarIOT</p>
</div>

</body> 
</html>