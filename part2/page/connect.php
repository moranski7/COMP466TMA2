<?php
	/* Used to connect to database.*/

	$servername = "localhost";
	/*User will need to create a new user account in the sql database
	and add the username and password to this file. Won't work otherwise.*/
	$username = "";
	$password = "";
	$db = "tma2part2";

	$conn = mysqli_connect ($servername, $username, $password, $db);

	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		exit();
	}
?>
