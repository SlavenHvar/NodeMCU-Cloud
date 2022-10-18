<?php
    //Connect to database
    $db = mysqli_connect('localhost', 'id8537817_espdemo123', 'slaven123', 'id8537817_espdemo');

    if(!empty($_GET['device']))
    {
    	$device_name = $_GET['device'];//nodemcu šalje get request preko ove php datoteke serveru
		
        $query_status = "SELECT status FROM devices WHERE device = ?";

		$stmt = mysqli_prepare($db, $query_status);
		if ($stmt === false) {
			error_log(mysqli_error($db));
			die("Sorry, there has been a software error");
		}
		$ok = mysqli_stmt_bind_param($stmt, "s", $device_name);
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
                echo $row["status"];
            }
        } 
	}
	mysqli_close($db);
?>