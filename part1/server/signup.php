<?php
	require_once "connect.php";

	/*
		Check for duplicate names in the users table.
		Return true if one or more instance of a username is in
		the table.
		Return false if no name in the table or if an error
		has occurred.
	*/
	function checkDupName($conn) {
		$sql = "SELECT userName FROM users WHERE userName = ?";
		$stmt = mysqli_prepare ($conn, $sql);
		$userName = $_POST["userName"];
		mysqli_stmt_bind_param($stmt, "s", $userName);

		if (mysqli_stmt_execute($stmt)) {
			mysqli_stmt_store_result($stmt);
			$result = mysqli_stmt_num_rows($stmt);
			mysqli_stmt_close($stmt);

			if ($result > 0)
				return true;
			else
				return false;
		}
		else {
			echo "Error in checkDupEmail().";
			return false;
		}
		return false;
	}

	/*
		Check for duplicate email address in the users table.
		Return true if one or more instance of an email address is in
		the table.
		Return false if no email in the table or if an error
		has occurred.
	*/
	function checkDupEmail ($conn) {
		$sql = "SELECT email FROM users WHERE email = ?";
		$stmt = mysqli_prepare ($conn, $sql);
		$email = $_POST["emailGet"];
		mysqli_stmt_bind_param($stmt, "s", $email);

		if (mysqli_stmt_execute($stmt)) {
			mysqli_stmt_store_result($stmt);
			$result = mysqli_stmt_num_rows($stmt);
			mysqli_stmt_close($stmt);

			if ($result > 0)
				return true;
			else
				return false;
		}
		else{
			echo "Error in checkDupEmail().";
			return false;
		}
		return false;
	}

	/*
		Makes sure the user passwords matches the confirmed password.
	*/
	function checkMatchingPasswords () {
		if ($_POST["makePassword"] == $_POST["confirmPassword"])
			return true;
		else
			return false;
	}

	/* Inserts the username, email address and password into the table.
	Password is hashed before inserting into the table.*/
	function insertIntoDB ($conn) {
		$paramUserName = $_POST["userName"];
		$paramEmail = $_POST["emailGet"];
		$hashPassword = password_hash($_POST["makePassword"], PASSWORD_DEFAULT);
		$sql = "INSERT INTO users (userName, email, password) VALUES (?,?,?)";

		$stmt = mysqli_prepare ($conn, $sql);
		mysqli_stmt_bind_param($stmt, "sss", $paramUserName,$paramEmail, $hashPassword);
		if(mysqli_stmt_execute($stmt)) {
			$url = "./login.php";
			header('Location: ' . $url);
		}
		else{
			echo "Something went wrong. Please try again later.";
        }
        
        mysqli_stmt_close($stmt);
	}

	/*Used to display any errors. Sends it the javascript side.*/
	function displayError ($errMsg) {
		echo "<script>". $errMsg . "</script>";
	}

	displayError ("errMsg = '';");

	//Occurs when the form posts to itself
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//Check for all fields are filled
		if ((!empty ($_POST["userName"])) && 
			(!empty ($_POST["emailGet"])) &&
			(!empty ($_POST["makePassword"])) &&
			(!empty ($_POST["confirmPassword"]))) {
			
			//Check for duplicate user name
			if (checkDupName($conn)) {
				$errMsg = "errMsg = 'User name already in use!';";
				displayError ($errMsg);
			}
			//Check for duplicate email
			else if (checkDupEmail($conn)) {
				$errMsg = "errMsg = 'Email already in use!';";
				displayError ($errMsg);
			}
			else if (!checkMatchingPasswords ()) {
				$errMsg = "errMsg = 'Password does not match confirm password!';";
				displayError ($errMsg);
			}
			else {
				insertIntoDB ($conn);
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
		<script src="../script/signup.js"></script>
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
			<form id="signUpForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<h2>Sign Up</h2>
				<div><a href="./login.php">Already have an acount? Login here!</a></div>
				<div>
					<label for="userName">User Name:</label>
					<input name="userName" id="userName" type="text" onblur="trimSignUpForm(id)" required/>
				</div>
				<div>
					<label for="emailGet">Email:</label>
					<input name="emailGet" id="emailGet" type="email" onblur="trimSignUpForm(id)" required/>
				</div>
				<div>
					<label for="makePassword">Password:</label>
					<input name="makePassword" id="makePassword" type="password" minlength="8" onblur="trimSignUpForm(id)" required/>
				</div>
				<div>
					<label for="confirmPassword">Confirm Password:</label>
					<input name="confirmPassword" id="confirmPassword" type="password" minlength="8" onblur="trimSignUpForm(id)" required/>
				</div>
				<div class="formButtonsCon">
					<input id="returnMain" type="button" value="Cancel" />
					<input id="submitUser" type="Submit" value="Submit" />
				</div>
				<div id="signUpErrors"></div>
				<p>Password must be a minimum of 8 characters in length. Passwords should contain a mixture of lowercase, uppercase, numbers and special characters.</p>
			</form>
		</div>
	</body>
</html>
