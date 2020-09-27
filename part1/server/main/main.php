<?php
	require_once "../connect.php";

	if(!isset($_SESSION))
		session_start();

	//Sends any errors to javascript side to be displayed.
	function displayResult ($msg) {
		echo "<script> data= '" .$msg."';</script>";
	}

	//https://stackoverflow.com/questions/2280394/how-can-i-check-if-a-url-exists-via-php
	function checkURLisValid ($url) {
		$urlHeaders = @get_headers($url);
		if (!$urlHeaders || $urlHeaders[0] == "HTTP/1.1 404 Not Found") {
			return false;
		}
		else {
			return true;
		}
	}

	//Check for duplicate url per username in the bookmarks table.
	// true if more than one result
	// False if no result or errors.
	function duplicateURL ($conn, $url) {
		$sql = "SELECT url FROM bookmarks WHERE userName = ? AND url = ?";
		$stmt = mysqli_prepare ($conn, $sql);
		$userName = $_SESSION["userName"];
		$checkUrl = rtrim($url, "/"); //Removes any trailing /
		mysqli_stmt_bind_param($stmt, "ss", $userName, $checkUrl);

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
			displayResult ("Error in duplicateURL().");
			return false;
		}
		return false;
	}

	/*
		Check if matching id in the bookmarks table.
		If no id encourntered, false.
		If errors, false.
		True otherwise.
	*/	
	function checkForID ($conn, $id) {
		$sql = "SELECT url FROM bookmarks WHERE userName = ? AND id = ?";
		$stmt = mysqli_prepare ($conn, $sql);
		$userName = $_SESSION["userName"];
		$checkID = $id;
		mysqli_stmt_bind_param($stmt, "ss", $userName, $checkID);

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
			displayResult ("Error in checkForID().");
			return false;
		}
		return false;
	}

	/*
		Inserts url into bookmarks table.
		Display the result/errors on the main page.
	*/
	function insertIntoTable ($conn) {
		$paramUserName = $_SESSION["userName"];
		$paramURL = rtrim($_POST["addURLData"], "/"); //Removes any trailing /
		$sql = "INSERT INTO bookmarks (ID, userName, URL) VALUES (NULL, ?, ?)";

		$stmt = mysqli_prepare ($conn, $sql);
		mysqli_stmt_bind_param ($stmt, "ss", $paramUserName, $paramURL);
		if(mysqli_stmt_execute($stmt)) {
			displayResult ("URL added!");
		}
		else {
			displayResult ("Something went wrong. Please try again later.");
		}

		mysqli_stmt_close($stmt);
	}

	// If not logged in, redirect to login page.
	if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true || $_SESSION["part1"] !== true) {
		header("location: ../login.php");
	}

	/*
		Updates the url matching the id in the bookmarks table.
		Display the result/errors on the main page.
	*/
	function updateTable ($conn, $url, $id) {
		$paramId = $id;
		$paramURL = rtrim($url, "/"); //Removes any trailing /
		$sql = "UPDATE bookmarks SET URL = ? WHERE ID = ?";

		$stmt = mysqli_prepare ($conn, $sql);
		mysqli_stmt_bind_param ($stmt, "ss", $paramURL, $paramId);
		if(mysqli_stmt_execute($stmt)) {
			displayResult ("URL modified!");
		}
		else {
			displayResult ("Something went wrong. Please try again later.");
		}

		mysqli_stmt_close($stmt);
	}

	/*
		Removes any url matching the id from the bookmarks table.
		Display the result/errors on the main page.
	*/
	function deleteFromTable ($conn, $id) {
		$paramId = $id;
		$sql = "DELETE FROM bookmarks WHERE ID = ?";
		$stmt = mysqli_prepare ($conn, $sql);

		mysqli_stmt_bind_param ($stmt, "s", $paramId);
		if (mysqli_stmt_execute($stmt)) {
			displayResult ("URL removed!");
		}
		else {
			displayResult ("Something went wrong. Please try again later.");
		}

		mysqli_stmt_close ($stmt);
	}

	displayResult (""); //init the variable in the javascript. Don't remove.
	//If post to self and $_POST is set.
	if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset ($_POST["addURLData"]))) {
		// check for empty fields
		if (!empty($_POST["addURLData"])) {
			//Make sure the URL is valid
			if (!checkURLisValid ($_POST["addURLData"])) {
				displayResult ("Not a valid URL: " . $_POST["addURLData"]);
			}
			//Make sure there are no duplicate url
			else if (duplicateURL ($conn, $_POST["addURLData"])) {
				displayResult ("Duplicate URL: " . $_POST["addURLData"]);
			}
			else {
				insertIntoTable ($conn);
			}
		}
	}

	//If post to self and $_POST is set.
	if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset ($_POST["deleteURLData"]))) {
		// check for empty fields
		if (!empty($_POST["deleteURLData"])) {
			//Check to make sure the id is in the table
			if (!checkForID ($conn, $_POST["deleteURLData"])) {
				displayResult ("No ID matching: " . $_POST["deleteURLData"]);
			}
			else {
				deleteFromTable ($conn, $_POST["deleteURLData"]);
			}
		}
		
	}

	//If post to self and $_POST is set.
	if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset ($_POST["editFromURL"])) && (isset ($_POST["editToURL"]))) {
		// check for empty fields
		if ((!empty($_POST["editFromURL"])) && (!empty($_POST["editToURL"]))) {
			//Make sure the URL is valid
			if (!checkURLisValid ($_POST["editToURL"])) {
				displayResult ("Not a valid URL: " . $_POST["editToURL"]);
			}
			//Make sure there are no duplicate url
			else if (duplicateURL ($conn, $_POST["editToURL"])) {
				displayResult ("new URL is Duplicate: " . $_POST["editToURL"]);
			}
			//Check to make sure the id is in the table
			else if (!checkForID ($conn, $_POST["editFromURL"])) {
				displayResult ("No ID matching: " . $_POST["editFromURL"]);
			}
			else {
				updateTable ($conn, $_POST["editToURL"], $_POST["editFromURL"]);
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Digiary</title>
		<link rel = "stylesheet" type = "text/css" href = "../../../shared/tma2.css" />
		<script src="../../script/main.js"></script>
	</head>
	<body>
		<div class="naviCon">
			<a href="#">PART 1</a>
			<div class="homeCon"><a href="../../../tma2.htm"><img src="../../../shared/icons/home.png" alt="home"></a></div>
			<a id="part2Link" href="../../../part2/part2.php">PART 2</a>
		</div>
		<div class="bannerCon">
			<img id="part1Banner" src="./../../media/Digiary.png" alt="Digiary"/>
		</div>

		<div class="displayUsername">
			Signed in as <em><?php echo $_SESSION["userName"];?></em>
			<div><a id="logoutOpt" href="#">logout?</a></div>
		</div>
		<div class="bookmarkMenu">
			<img src="../../../shared/icons/Hamburger_icon.png" alt="Menu" id="menuIcon"/>
			<div class="bookmarkMenu-content">
				<a id="addURL">Add</a>
				<a id="deleteURL">Delete</a>
				<a id="editURL">Edit</a>
			</div>
		</div>
		<div id="menuStagingArea" class="stagingArea"></div>
		<div id="resultURL" class="urlFormResult"></div>
		<div id="bookmarkStorage">
			<table class="urlTable" id="tableStorage">
				<tr>
					<th>ID</th>
					<th>URL</th>
				</tr>
			</table>
		</div>
	</body>
</html>

<script>
	/*
		Creates a row in the user bookmark
		table. Row contains two cols. Col one
		is id, col two is the url.
	*/
	function createLink (id, url) {
		var row = document.createElement("tr");
		var colOne = document.createElement("td");
		var colTwo = document.createElement("td");
		var link = document.createElement ("a");
		var nodeId = document.createTextNode(id);
		var nodeUrl = document.createTextNode(url);

		row.appendChild(colOne);
		row.appendChild(colTwo);
		colOne.appendChild (nodeId);
		colTwo.appendChild (link);
		link.appendChild (nodeUrl);

		link.setAttribute ("href", url);
		link.setAttribute ("target", "_blank");
		link.setAttribute ("rel", "noopener noreferrer");
		colOne.setAttribute ("class", "idCol");

		var table = document.getElementById("tableStorage");
		table.appendChild(row);
	}
</script>
<?php
	if(isset($_SESSION["userName"])) {
		$targetUsrName = $_SESSION["userName"];
		$sql = "SELECT id, url FROM bookmarks WHERE userName = ?";
		$stmt = mysqli_prepare ($conn, $sql);
		mysqli_stmt_bind_param($stmt, "s", $targetUsrName);
		if (mysqli_stmt_execute($stmt)) {
			$result = $stmt->get_result();
			while ($row = $result->fetch_array(MYSQLI_NUM)) {
				//Sends the data to javascript to be appended to the table
				echo "<script>".
					"createLink('".$row[0]."', '".$row[1]."');".
					"</script>";
			}

			mysqli_stmt_close($stmt);
		}
		else {
			echo "Something went wrong. Please try again later.";
		}
	}
?>