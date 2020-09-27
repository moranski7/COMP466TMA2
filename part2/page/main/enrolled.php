<?php
	require_once "../connect.php";

	if(!isset($_SESSION))
		session_start();

	// If not logged in, redirect to login page.
	if (!isset($_SESSION["loggedIn2"]) || $_SESSION["loggedIn2"] !== true || $_SESSION["part2"] !== true) {
		header("location: ../../part2.php");
	}

	/*
		Displays any messages/errors in the server.
		Uses javascript side to actually dispaly the message.
	*/
	function displayMsg ($msg) {
		echo "<script>data = '". $msg ."';</script>";
	}

	/*
		Used to get any info about the student from the student table.
	*/
	function getPersionalInfo ($conn, $userName, $col) {
		$sql = "SELECT ".$col." FROM students WHERE userName = ?";
		$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmt, "s", $userName);

		if (mysqli_stmt_execute ($stmt)) {
			mysqli_stmt_store_result ($stmt);

			if (mysqli_stmt_num_rows($stmt) == 1) {
				mysqli_stmt_bind_result ($stmt, $result);
				if (mysqli_stmt_fetch($stmt)) {
					$_SESSION[$col]=$result;
				}
				else { 
					displayMsg ("error in getPersionalInfo() mysqli_stmt_fetch");
				}
			}
			else { 
				displayMsg ("error in getPersionalInfo() mysqli_stmt_num_rows");
			}
		}
		else {
			displayMsg ("Something went wrong. Can't connect.");
		}
	}

	/*
		Check to make sure the lesson exist in the table.
		true= yes. False = no.
	*/
	function checkForLesson ($conn, $studentID, $lessonID) {
		$sql = "SELECT * FROM `lessons Registered` WHERE `student ID` = ? AND `Course ID` = ?";
		$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmt, "ii", $studentID, $lessonID);

		if (mysqli_stmt_execute ($stmt)) {
			mysqli_stmt_store_result ($stmt);
			$result = mysqli_stmt_num_rows ($stmt);
			mysqli_stmt_close ($stmt);

			if ($result > 0)
				return true;
			else
				return false;
		}
		else {
			displayMsg ("Something went wrong. Please try again later.");
			return false;
		}
		return false;
	}

	/*
		Gets the description for a lesson. 
		Success  = string
		fail = empty string.
	*/
	function getDescription ($conn, $lessonId) {
		$sql = "SELECT `Description` FROM `lesson description` WHERE `Course ID` = ?";
		$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmt, "s", $lessonId);

		if (mysqli_stmt_execute ($stmt)) {
			mysqli_stmt_store_result ($stmt);
			
			if (mysqli_stmt_num_rows($stmt) == 1) {
				mysqli_stmt_bind_result ($stmt, $result);
				
				if (mysqli_stmt_fetch($stmt)) {
					return $result;
				}
				else {
					displayMsg ("error in getDescription() mysqli_stmt_fetch");
					return "";
				}
			}
			else {
				displayMsg ("error in getDescription() mysqli_stmt_num_rows");
				return "";
			}
		}
		else {
			displayMsg ("Something went wrong. Can't connect.");
			return "";
		}
		return "";
	}

	/*
		Displays any and all registered lesson under the student's id.
		Sends the result to the javascript side to be displayed to screen
	*/
	function displayRegisteredLessons ($conn, $id) {
		/*
			SQL command is any lesson in the lesson table that matches any lessons in the lessons registered table that are paired with the student's id.
		*/
		$sql = "SELECT * FROM `lessons` LEFT JOIN (SELECT * FROM `lessons registered` WHERE `student ID` = ?) AS tempTable ON `lessons`.`Course ID` = `tempTable`.`Course ID` WHERE `tempTable`.`Course ID` IS NOT NULL";
		$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmt, "s", $id);

		if (mysqli_stmt_execute ($stmt)) {
			$result = $stmt->get_result();

			while ($row = $result->fetch_array(MYSQLI_NUM)) {
				$descript = getDescription ($conn, $row[0]);	//Gets the lessons description
				echo "<script>createAttendOpt ('".$row[0]."',". //Sends the result to the javascript side to be displayed to screen
					"'".$row[1]."', '".$row[2]."',".
					" '".$row[4]."', '$descript');</script>";

			}
			mysqli_stmt_close ($stmt);
		}
		else {
			displayMsg ("Something went wrong. Please try again later.");
		}
	}

	/*
		Removes the student from a lesson. 
	*/
	function disenrollFromLesson ($conn, $studentID, $lessonID) {
		$sql = "DELETE FROM `lessons registered` WHERE `student ID` = ? AND `Course ID` =?";
		$stmt = mysqli_prepare ($conn, $sql) or die(mysqli_error($conn));
		mysqli_stmt_bind_param ($stmt, "ii", $studentID, $lessonID);
		if (mysqli_stmt_execute($stmt)) {
			displayMsg  ("Successfully disenrolled from lesson!");
		}
		else {
			displayMsg  ("Something went wrong. Please try again later.");
		}

		mysqli_stmt_close ($stmt);
	}

	/*
		redirects to another page inorder to start lesson.
	*/
	function startLesson ($lessonID) {
		$_SESSION["emlID"] = $lessonID;
		header ("Location: ./eml.php");
	}

	//Check to makes use userName  is set. Otherwise student may have accessed page accidentally
	if (isset($_SESSION["userName"])) {
		displayMsg ("");
		
		if (!isset($_SESSION["firstName"]))
			getPersionalInfo ($conn, $_SESSION["userName"], "firstName");

		if (!isset($_SESSION["ID"]))
			getPersionalInfo ($conn, $_SESSION["userName"], "ID");
	}

	if (($_SERVER["REQUEST_METHOD"] == "POST")) {
		$arr = array_keys($_POST); //Gets a list of all keys in the array

		if (isset($_POST["startLesson"])) {
			if (count($arr) == 2) {
				$lessonID = intval($_POST[$arr[1]]);
				startLesson ($lessonID);
			}
			else {
				displayMsg ("Must first select a lesson inorder to start it."); 
			}
		}
		else if (isset($_POST["removeLesson"])) {
			if (count($arr) == 2) {
				$lessonID = intval($_POST[$arr[1]]);
				$studentID = intval($_SESSION["ID"]);
				
				if (checkForLesson ($conn, $studentID, $lessonID)) {
					disenrollFromLesson ($conn, $studentID, $lessonID);
				}
				else {
					displayMsg ("No lesson not in database. Please pick another.");
				}
			}
			else {
				displayMsg ("Must first select a lesson inorder to disenroll from it.");
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset = "utf-8" />
		<title>The Learning Vault</title>
		<link rel="stylesheet" type="text/css" href="../../../shared/tma2.css" />
		<script src="../../script/main.js"></script>
	</head>
	</head>
	<body>
		<div class="naviCon">
			<a href="../../../part1/part1.php">PART 1</a>
			<div class="homeCon"><a href="../../../tma2.htm"><img src="../../../shared/icons/home.png" alt="home"></a></div>
			<a id="part2Link" href="#">PART 2</a>
		</div>
		<div class="bannerCon">
			<img id="part2Banner" src="../../media/LearningVault.png" alt="Learning Vault"/>
		</div>
		<div id="studentIdPost" class="idPost">
			<h2>
				Welcome, <?php
					if(isset($_SESSION["firstName"]))
						echo $_SESSION["firstName"];
				?>
			</h2>
			Signed in as <?php
				if(isset($_SESSION["userName"]))
					echo $_SESSION["userName"];
			?>
			<div><a id="logoutOpt" href="#">logout?</a></div>
		</div> <!--ID Post-->
		<!--Utility Area. AJAX post to this area-->
		<div id="UtilityArea" class="utility">
			<div id="part2Menu" class="menu">
				<label for="mainMenu">Choose to: </label>
				<select name="mainMenu" id="mainMenu" onchange="location = this.value;">
					<option value="enroll.php">Enroll</option>
					<option value="enrolled.php" selected="selected">Start Lesson</option>
				</select>
			</div> <!--Menu-->
			<div id="lessonStagingArea">
				<div>
					<div class="subMenu"> <!--id="subMenuPart2"-->
						<button type="submit" form="lessonsRegistered" class="registerButtons" name="startLesson" value="Start">Start</button>
						<button type="submit"  form="lessonsRegistered" class="registerButtons" name="removeLesson" value="Disenroll">Disenroll</button>
						<div id="lessonDispalyResult">
						</div>
					</div> <!--sub Menu-->
					<div id="lessonsRegisteredCon" class="emlDisplayCon">
						<form name="lessonsRegistered" id="lessonsRegistered" class="optionDisplay" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
							<table id="registeredTable">
								<tr>
									<th></th>
									<th>ID</th>
									<th>Name</th>
									<th>Subject</th>
									<th>Type</th>
									<th>Description</th>
								</tr>
							</table>
						</form>
					</div><!--Display-->
				</div>
				<div class="Instructions">
					<h2>Attending Lessons Instructions</h2>
					<p>To start a lesson, select one from the list and click "start".</p>
					<p>To disenroll from a lesson, select one from the list and click "disenroll".</p>
					<p>To enroll in a lesson, go to enroll page using the drop down menu in the left hand cornor.</p> 
				</div>
				<h1 id="displayTitle">Lessons Registered</h1>
			</div>
		</div>
	</body>
</html>

<?php
	if (isset($_SESSION["userName"])) {
		displayRegisteredLessons ($conn, $_SESSION["ID"]);
	}
?>
