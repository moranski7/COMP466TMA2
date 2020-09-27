<?php
	require_once "connect.php";

	if(!isset($_SESSION))
		session_start();

	//Checks to see if the user is already logged in. Redirect if so.
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true && $_SESSION["part1"] === true) {
		header("location: ./main/main.php");
	}

	//Sends any errors to the javascript side.
	function displayErr($errMsg) {
		echo "<script>";
		echo $errMsg;
		echo "</script>";
	}

	displayErr ("errMsg = '';");


	//Process when form post to self
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//check for empty fields
		if (empty($_POST["enterUserName"])) {
			displayErr ("errMsg ='Please enter your user name.';");
		}
		else if (empty($_POST["enterPassword"])) {
			displayErr ("errMsg ='Please enter your password.';");
		}
		else if ((!empty($_POST["enterUserName"])) && 
				(!empty($_POST["enterPassword"]))) {

			//Create and execute sql query
			$sql = "SELECT userName, password FROM users WHERE userName = ?";
			$stmt = mysqli_prepare ($conn, $sql);
			$paramUserName = $_POST["enterUserName"];
			mysqli_stmt_bind_param ($stmt, "s", $paramUserName);
			
			if (mysqli_stmt_execute($stmt)) {
				mysqli_stmt_store_result($stmt);
				
				if (mysqli_stmt_num_rows($stmt)==1) {
					mysqli_stmt_bind_result ($stmt, $userName, $hashPswd);
					
					if (mysqli_stmt_fetch($stmt)) {
						if (password_verify($_POST["enterPassword"], $hashPswd)) {
							//Setup log in in session
							if(!isset($_SESSION))
								session_start();
							$_SESSION["part1"] = true;
							$_SESSION["loggedIn"] = true;
							$_SESSION["userName"] = $userName;

							//Redirect to main page
							header("location: ./main/main.php");
						}
						else {
							displayErr ("errMsg ='login/password not correct.';");
						}
					}
					else {
						displayErr ("errMsg ='Problem with mysqli_stmt_fetch';");
					}
				}
				else {
					displayErr ("errMsg ='login/password not correct.';");
				}
			}
			else {
				displayErr ("errMsg ='Can't connect. Please try again later.';");
			}
		}
	}

	// Close connection
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>TMA2: Part 2</title>
		<link rel = "stylesheet" type = "text/css" href = "../../shared/tma2.css" />
		<script src="../script/login.js"></script>
	</head>
	<body>
		<div class="naviCon">
			<a href="#">PART 1</a>
			<div class="homeCon"><a href="../../tma2.htm"><img src="../../shared/icons/home.png" alt="home"></a></div>
			<a id="part2Link" href="../../part2/part2.php">PART 2</a>
		</div>
		<div class="bannerCon">
			<img id="part1Banner" src="./../media/Digiary.png" alt="Digiary"/>
		</div>
		<div class="formCon">
			<form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<h2>Log In</h2>
				<div>
					<label for="enterUserName">User Name:</label>
					<input name="enterUserName" id="enterUserName" type="text" required/>
				</div>
				<div>
					<label for="enterPassword">Password:</label>
					<input name="enterPassword" id="enterPassword" type="password" required/>
				</div>
				<a href="./signup.php">No account? Register here!</a>
				<div id="loginResult"></div>
				<div class="formButtonsCon">
					<input id="returnMain" type="button" value="Cancel" />
					<input id="loginUser" type="Submit" value="Log In" />
				</div>
			</form>
		</div>
		<script>
			function trimSignUpForm(id) {
				input = document.getElementById(id);
				input.value = input.value.trim();
			}
		</script>
	</body>
</html>