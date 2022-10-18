<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Register new user</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body style="background-color:#D3D3D3">
  
  <div class="container">
	<h2>Register new user</h2>
	<br/>
		<form method="post" action="register.php">
		<?php include('errors.php'); ?>
			<div class="form-group">
			  <label>Username:</label>
			  <input type="text" name="username" value="<?php echo $username; ?>" class="form-control" placeholder="Enter username">
			</div>
			<div class="form-group">
			  <label>Email:</label>
			  <input type="email" name="email" value="<?php echo $email; ?>" class="form-control" id="email" placeholder="Enter email">
			</div>
			<div class="form-group">
			  <label>Password:</label>
			  <input type="password" name="password_1" class="form-control" id="pwd" placeholder="Enter password">
			</div>
			<div class="form-group">
			  <label>Confirm password:</label>
			  <input type="password" name="password_2" class="form-control" id="pwd" placeholder="Confirm password">
			</div>
			<button type="submit" class="btn btn-default" name="reg_user">Save</button>
		</form>
</div>
  
</body>
</html>