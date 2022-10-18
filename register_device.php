<?php include('server_devices.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Register new device</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body style="background-color:#D3D3D3">
  <div class="container">
  <h2>Register new device</h2>
  <form method="post" action="register_device.php">
  <?php include('errors.php'); ?>
    <div class="form-group">
      <label>Device name:</label>
      <input type="text" name="device" value="<?php echo $device; ?>" class="form-control" placeholder="Enter device name">
    </div>
    <div class="form-group">
      <label>Device description:</label>
      <input type="text" name="description" value="<?php echo $description; ?>" class="form-control" placeholder="Enter device description" >
    </div>
	<div class="form-group">
      <label>Device type:</label>
		<select type="text" name="deviceType" class="form-control" placeholder="Select device type">
			<option value="SWITCH">Switch</option>
			<option value="SENSOR">Sensor</option>
		</select>
    </div>
    <div class="form-group">
      <label>Select user:</label>
		<select type="text" value="<?php echo $username; ?>" name="username" class="form-control" placeholder="Select user">
		<?php
		    $db = mysqli_connect('localhost', 'id8537817_espdemo123', 'slaven123', 'id8537817_espdemo');
			$query = "SELECT * FROM users";
			$stmt = mysqli_prepare($db, $query);
			if ($stmt === false) {
				error_log(mysqli_error($db));
				die("Sorry, there has been a software error");
				echo "<option>error</option>";
			}
			$ok = mysqli_stmt_execute($stmt);
			if ($ok === false) {
				error_log(mysqli_stmt_error($db));
				die("Sorry, there has been a software error");
				echo "<option>error</option>";
			}
			$result = $stmt->get_result();
			if(empty($result))
			{
				echo "<option>No users</option>";
			}
			while($row = $result->fetch_array()){                                                 
				echo "<option>".$row['username']."</option>";
			}
			mysqli_close($db);
		?>
		</select>
    </div>
    <button type="submit" name="reg_user" class="btn btn-default">Save</button>
  </form>
  
</div>
  
</body>
</html>