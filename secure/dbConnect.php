<?php
	$host = "localhost";
	$dbname = "doctorsapp";
	$user = "root";
	$pass = "";	
		
	$conn = mysqli_connect($host, $user, $pass, $dbname);	
	
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}	
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	echo "<p style='align:center'>Connected to data server successfully</p>";
?>