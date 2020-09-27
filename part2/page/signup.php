<?php
	require_once "connect.php";

	/*
		Used to display any error or message from the php side.
		Uses Javascript to display messages.
	*/
	function displayError ($errMsg) {
		echo "<script>errMsg = '". $errMsg . "';</script>";
	}

	/*
		Checks to makes sure all fields in sign up form are filled.
	*/
	function allFilled () {
		if (!empty($_POST["userName"]) || !empty($_POST["firstName"]) ||
			!empty($_POST["lastName"]) || !empty($_POST["age"]) ||
			!empty($_POST["emailGet"]) || !empty($_POST["makePassword"]) ||
			!empty($_POST["confirmPasswordCon"])) {
			return true;
		}
		else
			return false;
	}

	/*
		Uses SELECT to search the database under the condition
		specified in var param.
	*/
	function sqlSelect ($conn, $param, $sql) {
		$stmt = mysqli_prepare ($conn, $sql);
		mysqli_stmt_bind_param ($stmt, "s", $param);

		if (mysqli_stmt_execute($stmt)) {
			mysqli_stmt_store_result($stmt);
			$result = mysqli_stmt_num_rows($stmt);
			mysqli_stmt_close ($stmt);

			if ($result > 0)
				return true;
			else
				return false;
		}
		else {
			displayError ("Something went wrong. Please try again later.");
			return false;
		}
		return false;
	}

	/*
		Check for duplicate userName in the database.
	*/
	function isUserNameDup ($conn, $userName) {
		$sql = "SELECT userName FROM students where userName = ?";
		$param = $userName;

		return sqlSelect ($conn, $param, $sql);
	}

	/*
		Check for duplicate email in the database
	*/
	function isEmailDup ($conn, $email) {
		$sql = "SELECT userName FROM students where email = ?";
		$param = $email;
		return sqlSelect ($conn, $param, $sql);
	}

	/*
		Makes sure the user passwords matches the confirmed password.
	*/
	function matchingPasswords () {
		if ($_POST["makePassword"] == $_POST["confirmPassword"])
			return true;
		else
			return false;
	}


	/*
		Insert new user into the database.
		On success redirects web page to enroll.php
	*/
	function insertIntoDB ($conn) {
		$paramUser = $_POST["userName"];
		$paramFirst = $_POST["firstName"];
		$paramLast = $_POST["lastName"];
		$paramAge = intval($_POST["age"]);
		$paramEmail = $_POST["emailGet"];
		$hashpassword = password_hash($_POST["makePassword"], PASSWORD_DEFAULT);

		$sql = "INSERT INTO students (ID, userName, firstName, lastName, Age, email, password) VALUES (NULL, ?, ?, ?, ?, ?, ?)";
		$stmt = mysqli_prepare ($conn, $sql);
		mysqli_stmt_bind_param ($stmt, "sssiss", $paramUser, $paramFirst, $paramLast, $paramAge, $paramEmail, $hashpassword);
		if (mysqli_stmt_execute ($stmt)) {
			header ("Location: ../part2.php");
		}
		else {
			displayError ("Something went wrong. Please try again later.");
		}

		mysqli_stmt_close($stmt);
	}

	displayError (""); //init the variable in javascript side.

	//Occurs when the form posts to itself
	if ($_SERVER["REQUEST_METHOD"] ==  "POST") {
		//Check for all fields are filled
		if (allFilled ()) {
			//Since username and email are primary key and unique constraint, check for duplicates
			if (isUserNameDup ($conn, $_POST["userName"])) {
				displayError ("User name in use. Please choose another.");
			}
			else if (isEmailDup ($conn, $_POST["emailGet"])) {
				displayError ("Another account using that email. Please choose another.");
			}
			else if (!matchingPasswords ()) { //Confirm matching password and confirm password
				displayError ("Passwords don't match!");
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
		<title>Register</title>
		<link rel="stylesheet" type="text/css" href="../../shared/tma2.css" />
		<script src="../script/signup.js"></script>
	</head>
	<body>
		<div class="naviCon">
			<a href="../../part1/part1.php">PART 1</a>
			<div class="homeCon"><a href="../../tma2.htm"><img src="../../shared/icons/home.png" alt="home"></a></div>
			<a id="part2Link" href="#">PART 2</a>
		</div>
		<div class="bannerCon">
			<img id="part2Banner" src="../media/LearningVault.png" alt="Learning Vault"/>
		</div>
		<div id="part2SignUpForm" class="formCon">
			<form id="signUpForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<h2>Register</h2>
				<div><a href="./login.php">Already have an acount? Login here!</a></div>
				<div>
					<label for="userName">User Name:</label>
					<input name="userName" id="userName" type="text" onblur="trimSignUpForm(id)" required/>
				</div>
				<div>
					<div id="fullNameCon" class="personalInfo">
						<div>
							<label for="firstName">First Name:</label>
							<input name="firstName" id="firstName" type="text" onblur="trimSignUpForm(id)" required/>
						</div>
						<div id="lastNameCon" class="subPersonalInfo">
							<label for="lastName">Last Name:</label>
							<input name="lastName" id="lastName" type="text" onblur="trimSignUpForm(id)" required/>
						</div>
					</div>
					<div id="ageEmailCon" class="personalInfo">
						<div>
							<label for="age">Age:</label>
							<input name="age" id="age" type="number" min="0" required/>
						</div>
						<div id="emailCon" class="subPersonalInfo">
							<label for="emailGet">Email:</label>
							<input name="emailGet" id="emailGet" type="email" onblur="trimSignUpForm(id)" required/>
						</div>
					</div>
				</div>
				<div id="passwordsCon" class="personalInfo">
					<div>
						<label for="makePassword">Password:</label>
						<input name="makePassword" id="makePassword" type="password" minlength="8" onblur="trimSignUpForm(id)" required/>
					</div>
					<div id= "confirmPasswordCon" class="subPersonalInfo">
						<label for="confirmPassword">Confirm Password:</label>
						<input name="confirmPassword" id="confirmPassword" type="password" minlength="8" onblur="trimSignUpForm(id)" required/>
					</div>
				</div>
				<p>Password must be a minimum of 8 characters in length. Passwords should contain a mixture of lowercase, uppercase, numbers and special characters.</p>
				<div id="part2formButtonsCon" class="formButtonsCon">
					<input id="returnMain" type="button" value="Cancel" />
					<input id="submitUser" type="Submit" value="Submit" />
				</div>
				<div id="signUpErrorsPart2"></div>
			</form>
		</div>
	</body>
</html>
