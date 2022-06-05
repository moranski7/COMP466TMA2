<?php
	require_once "../connect.php";

	if(!isset($_SESSION))
		session_start();

	// If not logged in, redirect to login page.
	if (!isset($_SESSION["loggedIn2"]) || $_SESSION["loggedIn2"] !== true || $_SESSION["part2"] !== true) {
		header("location: ../../part2.php");
	}

	/*
		Displays any error/msg to user.
		Uses Javascript.
	*/
	function displayMsg ($msg) {
		echo "<script>data = '". $msg ."';</script>";
	}

	/*
		Query the database for a col. WHERE condition of sql is optional.
		Store the result in Sesssion array.
	*/
	function getInfo ($conn, $col, $table, $where = "", $param="") {
		$sql = "SELECT ".$col." FROM ".$table. " ". $where;
		$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));

		if ((!empty($param)) && (!empty($where)))
			mysqli_stmt_bind_param($stmt, "s", $param);

		if (mysqli_stmt_execute ($stmt)) {
			mysqli_stmt_store_result ($stmt);

			if (mysqli_stmt_num_rows($stmt) == 1) {
				mysqli_stmt_bind_result ($stmt, $result);
				if (mysqli_stmt_fetch($stmt)) {
					$_SESSION[$col]=$result;
				}
				else { 
					displayMsg ("error in getInfo () mysqli_stmt_fetch");
				}
			}
			else { 
				displayMsg ("error in getInfo () mysqli_stmt_num_rows");
			}
		}
		else {
			displayMsg ("Something went wrong. Can't connect.");
		}
	}

	/*
		Used to query the student table for a specific col. Store in Session array.
	*/
	function getPersionalInfo ($conn, $userName, $col) {
		getInfo ($conn, $col, "students", "WHERE userName = ?", $userName);
	}

	/*
		Used to query the lesson table for a specific col. Store in Session array.
	*/
	function getCourseInfo ($conn, $courseId, $col) {
		getInfo ($conn, $col, "lessons", "WHERE `Course ID` = ?", $courseId);
	}

	/*
		Query the lesson registered taqble for duplicated registration.
		true if already registered.
	*/
	function checkDuplicateRegistere($conn, $studentID, $courseID) {
		$sql = "SELECT `Course ID` FROM `lessons registered` WHERE `Student ID` = ? AND `Course ID` = ?";
		$stmt = mysqli_prepare ($conn, $sql);
		mysqli_stmt_bind_param($stmt, "ii", $studentID, $courseID);

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
		Gets the description for a lesson.
		Sucess returns a string. Fail returns empty string.
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
		Display any available lessons not enrolled in to the student.

	*/
	function displayAvailableLessons ($conn, $id) {
		/*SQL command is any lessons in that lesson table that do not have a
		corresponding lesson in the lesson registered table under the student's id.*/
		$sql = "SELECT * FROM `lessons` LEFT JOIN (SELECT * FROM `lessons registered` WHERE `student ID` = ?) AS tempTable ON `lessons`.`Course ID` = `tempTable`.`Course ID` WHERE `tempTable`.`Course ID` IS NULL";
		$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
		mysqli_stmt_bind_param($stmt, "s", $id);
		
		if (mysqli_stmt_execute ($stmt)) {
			$result = $stmt->get_result();

			while ($row = $result->fetch_array(MYSQLI_NUM)) {
				$descript = getDescription ($conn, $row[0]);
				echo "<script>createOption('".$row[0]."',"."'".$row[2]."', '".$row[3]."',"." '".$row[4]."','$descript');</script>"; //sensitive to columns order of the sql table. If not working, check row content. Might be trying to load content instead of decription.
			}
			mysqli_stmt_close ($stmt);
		}
		else {
			displayMsg ("Something went wrong. Please try again later.");
		}
	}

	/*
		Enrolls the student into a lesson.
	*/
	function registerLesson ($conn, $studentID, $lessonID, $curDate) {
		$sql = "INSERT INTO `lessons registered` (`Student ID`, `Course ID`, `Enrolled`) VALUES (?,?,?)";
		$stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
		mysqli_stmt_bind_param ($stmt, "iis", $studentID, $lessonID, $curDate);

		if (mysqli_stmt_execute($stmt)) {
			$lessonName = getCourseInfo ($conn, $lessonID, "Name");
		}
		else
			displayMsg ("Something went wrong. Please try again later.");

		mysqli_stmt_close($stmt);
	}

	if (isset($_SESSION["userName"])) {
		displayMsg ("");
		
		//Gets the learner's first name to be displayed on screen
		if (!isset($_SESSION["firstName"]))
			getPersionalInfo ($conn, $_SESSION["userName"], "firstName");

		//Gets the learner's ID. Needed for registration.
		if (!isset($_SESSION["ID"]))
			getPersionalInfo ($conn, $_SESSION["userName"], "ID");
	}

	//Occurs in the event of a POST
	if (($_SERVER["REQUEST_METHOD"] == "POST")) {
		$arr = array_keys($_POST);
		
		for ($i = 0; $i < count($arr); $i++) {
			if (!checkDuplicateRegistere($conn, intval($_SESSION["ID"]), intval($_POST[$arr[$i]]))) {
				registerLesson ($conn, intval($_SESSION["ID"]), intval($_POST[$arr[$i]]), date("Y-m-d"));
			}
			else {
				displayMsg ("Student already registered.");
			}
		} //End of For
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
					<option value="enroll.php" selected="selected">Enroll</option>
					<option value="enrolled.php">Start Lesson</option>
				</select>
			</div> <!--Menu-->
			<div id="lessonStagingArea">
				<div>
					<div class="subMenu"> <!--id="subMenuPart2"-->
						<button type="submit" form="emlDisplay" value="Enroll">Enroll</button>
						<div id="lessonDispalyResult">
						</div>
					</div> <!--sub Menu-->
					<div id="emlDisplayPart2" class="emlDisplayCon">
						<form name="emlDisplay" id="emlDisplay" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
							<table class="lessonTable" id="lessonStorage">
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
					<h2>Enrollment Instructions</h2>
					<p>To enroll in a lesson, click on one or more lessons from the of list of available lessons and then click on Enroll.</p>
					<p>To start a lesson, change from "Enroll" to "Start Lesson" in the left hand cornor.</p> 
				</div>
				<h1 id="displayTitle">Available Lessons</h1>
			</div>
		</div>
	</body>
</html>

<?php
	if (isset($_SESSION["userName"])) {
		displayAvailableLessons ($conn, $_SESSION["ID"]);
	}
?>
