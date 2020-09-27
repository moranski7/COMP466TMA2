<?php
	session_start();
	$_SESSION = array(); //Release any info in Session
	session_destroy();
	header("location: ../../part2.php"); //Redirect back to main
	exit();
?>
