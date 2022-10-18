<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>NODEMCU CLOUD</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body style="background-color:#D3D3D3">

<div class="container">
  <h1>NodeMCU Cloud</h1>
  <br/>
  <h2>Login</h2>
  <form method="post" action="login.php">
  <?php include('errors.php'); ?>
    <div class="form-group">
      <label>Username:</label>
      <input type="text" class="form-control" placeholder="Enter username" name="username">
    </div>
    <div class="form-group">
      <label>Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password">
    </div>
    <button type="submit" class="btn btn-default" name="login_user">Login</button>
  </form>
</div>
</body>
</html>

