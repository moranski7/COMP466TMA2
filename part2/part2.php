<?php
	require_once "./page/connect.php";

	if(!isset($_SESSION))
		session_start();

	//Checks to see if the user is already logged in. Redirect if so.
	if (isset($_SESSION["loggedIn2"]) && $_SESSION["loggedIn2"] === true && $_SESSION["part2"] === true) {
		header("location: ./page/main/enroll.php");
	}

	/*
		Displays any errors to screen.
		Uses javascript.
	*/
	function displayError ($errMsg) {
		echo "<script>errMsg = '". $errMsg . "';</script>";
	}

	function login ($conn, $userName, $password) {
		$sql = "SELECT userName, password FROM students WHERE userName = ?";
		$stmt = mysqli_prepare ($conn, $sql);
		mysqli_stmt_bind_param ($stmt, "s", $userName);

		if (mysqli_stmt_execute($stmt)) {
			mysqli_stmt_store_result ($stmt);

			if (mysqli_stmt_num_rows($stmt) == 1) {
				mysqli_stmt_bind_result  ($stmt, $tableUser, $hashPswd);

				if (mysqli_stmt_fetch($stmt)) {
					if (password_verify($password, $hashPswd)) {

						//Setup log in in session
							if(!isset($_SESSION))
								session_start();
							$_SESSION["part2"] = true;
							$_SESSION["loggedIn2"] = true;
							$_SESSION["userName"] = $tableUser;
							
							//Redirect to main page
							header("location: ./page/main/enroll.php");
					}
					else {
						//Display error: user name/password not correct.
						displayError ("User name/password not correct");
					} //end of check passwords
				}
				else {
					//Display error: problem with fetch
					displayError ("Problem with fetch");
				} // end of fetching results
			}
			else {
				//Display error: user name/password not correct.
				displayError ("User name/password not correct");
			} // end of check number of results
		}
		else {
			//Display error: Can't connect.
			displayError ("Something went wrong. Can't connect.");
		} //end of if execute
	}

	displayError ("");
	//Occurs when the form posts to itself
	if ($_SERVER["REQUEST_METHOD"] ==  "POST") {
		//Check for all fields are filled
		if (empty($_POST["enterUserName"])) {
			echo "Fail 1";
		}
		else if (empty($_POST["enterPassword"])) {
			echo "Fail 2";
		}
		else if ((!empty($_POST["enterUserName"])) && 
				(!empty($_POST["enterPassword"]))) {
			login ($conn, $_POST["enterUserName"], $_POST["enterPassword"]);
		}
		else {
			//Display error: something unexpected happen.
			displayError ("Something unexpected happen. Contact admin.");
		}
	} //End of if $_SERVER

	// Close connection
	mysqli_close($conn);
?>

<!DOCTYPE>
<html lang="en">
	<head>
		<meta charset = "utf-8" />
		<title>TMA2: Part 2</title>
		<link rel="stylesheet" type="text/css" href="../shared/tma2.css" />
		<script src="./script/part2.js"></script>
	</head>
	<body>
		<div class="naviCon">
			<a href="../part1/part1.php">PART 1</a>
			<div class="homeCon"><a href="../tma2.htm"><img src="../shared/icons/home.png" alt="home"></a></div>
			<a id="part2Link" href="#">PART 2</a>
		</div>
		<div class="bannerCon">
			<img id="part2Banner" src="./media/LearningVault.png" alt="Learning Vault"/>
		</div>
		<div id="welcomeCon">
			<p>Welcome to the Learning Vault! The Learning Vault is a free online educational website where students can access a large variety of lessons. Lessons covers various subjects from various level of education. Students can keep track of which lessons they are enrolled in. Lessons also contains quizzes to test the student's understanding and give feedback. Register/login to get started.</p>
		</div>
		<div class="formCon">
			<form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div>
					<label for="enterUserName">User Name:</label>
					<input name="enterUserName" id="enterUserName" type="text" required/>
				</div>
				<div>
					<label for="enterPassword">Password:</label>
					<input name="enterPassword" id="enterPassword" type="password" required/>
				</div>
				<div class="formButtonsCon">
					<input id="registerUser" type="button" value="Register" />
					<input id="loginUser" type="Submit" value="Log In" />
				</div>
				<div id="loginResult"></div>
			</form>
		</div>
	</body>
</html>
