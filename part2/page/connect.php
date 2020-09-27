<?php
	/* Used to connect to database.*/

	$servername = "localhost";
	$username = "moranski";
	$password = "%5bU+VyV4?";
	$db = "tma2part2";

	$conn = mysqli_connect ($servername, $username, $password, $db);

	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		exit();
	}
?>
