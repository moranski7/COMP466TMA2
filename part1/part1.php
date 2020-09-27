<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>TMA2: Part 1</title>
		<link rel = "stylesheet" type = "text/css" href = "../shared/tma2.css" />
		<script src="./script/part1.js"></script>
	</head>
	<body>
		<div class="naviCon">
			<a href="#">PART 1</a>
			<div class="homeCon"><a href="../tma2.htm"><img src="../shared/icons/home.png" alt="home"></a></div>
			<a id="part2Link" href="../part2/part2.php">PART 2</a>
		</div>
		<div class="bannerCon">
			<img id="part1Banner" src="./media/Digiary.png" alt="Digiary"/>
		</div>
		<!--This container change using AJAX.-->
		<div id="utitlityArea" class="mainLoadingArea">
			<div id="welcomeCon">
				<p>Welcome to Digiary! Digiary is an online bookmarking service. Keep track of all your bookmarks in one location. No need to search through all your devices for that one bookmark. Login or sign up to get started!</p>
				<div class="loginRegister">
					<button id="signUp" type="button">Sign Up</button>
					<button id="login" type="button">Login</button>
				</div>
			</div>
			<br/>
			<div class="demoCon">
				<h2>Ten Most Popular Websites:</h2>
				<div class="tenMostPopularCon">
					<table class="urlTable" id="topTen">
						<tr>
							<th>Rank</th>
							<th>URL</th>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
<script>
	/*
		Creates a row in the ten Most popular
		table. Row contains two cols. Col one
		is rank, col two is the url.
	*/
	function createTopTen (rank, url) {
		//console.log (rank +": " + url);
		var row = document.createElement("tr");
		var colOne = document.createElement("td");
		var colTwo = document.createElement("td");
		var link = document.createElement ("a");
		var nodeRank = document.createTextNode(rank);
		var nodeUrl = document.createTextNode(url);

		row.appendChild(colOne);
		row.appendChild(colTwo);
		colOne.appendChild (nodeRank);
		colTwo.appendChild (link);
		link.appendChild (nodeUrl);

		link.setAttribute ("href", url);
		link.setAttribute ("target", "_blank");
		link.setAttribute ("rel", "noopener noreferrer");
		colOne.setAttribute ("class", "idCol");

		var table = document.getElementById("topTen");
		table.appendChild(row);
	}
</script>
<?php
	require_once "./server/connect.php";
	$sql ="SELECT URL, COUNT(URL) as UrlFrequency from bookmarks GROUP BY URL ORDER BY UrlFrequency DESC";
	$stmt = mysqli_prepare ($conn, $sql);
	if (mysqli_stmt_execute($stmt)) {
		$result = $stmt -> get_result();
		$topTen = 1;
		/*Loop while there is still a row in the search result or the top ten
		has not been found yet.*/
		while (($row = $result->fetch_array(MYSQLI_NUM)) && ($topTen <= 10)) {
			echo "<script>createTopTen ('". $topTen."', '". $row[0]."');</script>";
			$topTen += 1;
		}
		mysqli_stmt_close($stmt);
	}
	else {
		echo "Something went wrong. Please try again later.";
	}
?>