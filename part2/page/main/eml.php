<?php
	require_once "../connect.php";

	if(!isset($_SESSION))
		session_start();

	// If not logged in, redirect to login page.
	if (!isset($_SESSION["loggedIn2"]) || $_SESSION["loggedIn2"] !== true || $_SESSION["part2"] !== true) {
		header("location: ../../part2.php");
	}

	$GLOBALS["sql"] = $conn; 	//Makes sql connection global. Used for getImage function.
	$ans = array (); 		//Stores all answers for a quiz.

	/*
		Removes any whitespace in the eml document.
	*/
	function rmWhiteSpace ($eml) {
		$eml = preg_replace('/\t/', '', $eml);
		$eml = preg_replace('/\n/', '', $eml);
		$eml = str_replace("\r", "", $eml);

		return $eml;
	}

	/*
		Gets the eml document from the database. Uses the lesson
		id to locate the document.
		Success = eml document.
		Fail = empty string.
	*/
	function getEml ($conn, $lessonID) {
		$sql = "SELECT `EML Doc` FROM `lessons` WHERE `Course ID` = ?";
		$stmt = mysqli_prepare ($conn, $sql) or die (mysqli_error($conn));
		mysqli_stmt_bind_param ($stmt, "s", $lessonID);

		if (mysqli_stmt_execute($stmt)) {
			$result = $stmt->get_result();
			$row = $result->fetch_array(MYSQLI_NUM);
			mysqli_stmt_close ($stmt);
			return $row[0];
		}
		else {
			echo "<h1>Error in getEML()!</h1>";
			return "";
		}
		return "";
	}

	/*
		Gets the image from the database. Uses the image name
		to locate it. 
		Success = return image
		Fail = empty string.
	*/
	function getImage ($conn, $name) {
		$sql = "SELECT Image FROM `images` WHERE `Image Name` = ?";
		$stmt = mysqli_prepare ($conn, $sql) or die (mysqli_error($conn));
		mysqli_stmt_bind_param ($stmt, "s", $name);

		if (mysqli_stmt_execute($stmt)) {
			$result = $stmt->get_result();
			$row = $result->fetch_array(MYSQLI_NUM);
			mysqli_stmt_close ($stmt);
			return $row[0];
		}
		else {
			echo "<h1>No image matching specified name!</h1>";
			return "";
		}
		return "";
	}

	/*
		Used to get the content between two destinated tags.
		Returns content minus tags on success or empty string on fail.
	*/
	function getBetweenTags ($string, $start, $end) {
		if (strpos ($string, $start) === false)
			return "";
		$first = strpos ($string, $start) + strlen($start); //starting location of tag plus length
		$second = strpos ($string, $end) - $first; //Starting location of tag minus length from first
		return substr ($string, $first, $second);
	}

	/*
		Used to remove tag pair and their content from a string.
		Return modified string on success. Otherwise returns
		unmodified string.
	*/
	function removeTags ($string, $remove) {
		if (strpos ($string, $remove) !== false)
			return substr($string, strlen($remove));
		else
			return $string;
	}

	/*
		Parse a paragraph from the EML document.
		Uses string replace method to add bold, italic, and
		code to the paragraph.
		Outputs to result to screen.
	*/
	function parsePar ($par) {
		$par = str_replace ("<bold>", "<strong>", $par);
		$par = str_replace ("</bold>", "</strong>", $par);

		$par = str_replace ("<italic>", "<em>", $par);
		$par = str_replace ("</italic>", "</em>", $par);

		$par = str_replace ("<external link", "<a href", $par);
		$par = str_replace ("</external>", "</a>", $par);

		$par = str_replace ("<codeExample>", "<code>", $par);
		$par = str_replace ("</codeExample>", "</code>", $par);

		echo "<p>".$par."</p>";
	}

	/*
		Parse a definition from the EML document.
		Uses string replace method to add bold and italic to the paragraph.
		Outputs to result to screen.
	*/
	function parseDef ($def) {
		$def = str_replace ("<bold>", "<strong>", $def);
		$def = str_replace ("</bold>", "</strong>", $def);

		$def = str_replace ("<italic>", "<em>", $def);
		$def = str_replace ("</italic>", "</em>", $def);

		echo "<div class='lessonDef'>".$def."</div>";
	}


	/*
		Parse an image tag from the eml document.
		Name subtag are used to get the image from the database.
		Caption subtag are used to place a caption for an image.
		Outputs result to screen.

		NOTE: Function uses $GLOBALS to access the var $conn which is a link to the database.
	*/
	function parseImage ($image) {
		$hold = "<figure>";

		//Continue while there is still a subtag to be parsed.
		while (strlen($image) >1) {
			$tag = getBetweenTags ($image, "<", ">");

			switch ($tag) {
				case "name"		:	$name = getBetweenTags ($image, "<name>", "</name>"); //Get the contents from the subtag
									$image = removeTags ($image, "<name>".$name."</name>"); //Remove the subtag.
									$imageHold = getImage ($GLOBALS["sql"], $name); //Gets image from the database
									$hold .= "<img src=$imageHold alt=$name/>";
									break;

				case "caption"	: 	$caption = getBetweenTags ($image, "<caption>", "</caption>");
									$image = removeTags ($image, "<caption>".$caption."</caption>");
									$hold .= "<figcaption>".$caption."</figcaption>";
									break;
			}
		}

		$hold .= "</figure>";
		echo $hold;
	}


	/*
		Parse an introduction from the EML document.
		Uses string replace method to add bold and italic to the introduction.
		Outputs to result to screen.
	*/
	function parseIntro ($intro) {
		$intro = str_replace ("<bold>", "<strong>", $intro);
		$intro = str_replace ("</bold>", "</strong>", $intro);

		$intro = str_replace ("<italic>", "<em>", $intro);
		$intro = str_replace ("</italic>", "</em>", $intro);
		echo "<div class='lessonIntro'>".$intro."</div>";
	}

	/*
		Parse a list tag from the eml document.
		Retrieves any subtags and parse them.
		Displays the result to screen.
	*/
	function parseList ($list) {
		$hold = "<ul>";

		//Continue while there is still a subtag to be parsed.
		while (strlen($list) >1) {
			$bullet = getBetweenTags ($list, "<bullet>", "</bullet>"); //Get the contents from the subtag
			$list = removeTags ($list, "<bullet>".$bullet."</bullet>"); //Remove the subtag from list.

			//Check to see if bullet contains any code subtag.
			if (strpos ($bullet, "<codeExample>") !== false) {
				$bullet = str_replace ("<codeExample>", "<code>", $bullet); //Use str_replace to parse
				$bullet = str_replace ("</codeExample>", "</code>", $bullet);
			}
			$hold .= "<li>".$bullet."</li>"; 
		}
		$hold .= "</ul>";
		echo $hold;
	}

	/*
		Parse a content tag from the eml document.
		Retrieves any subtags within the content and parse them.
		Display results to screen
	*/
	function parseContent ($content) {
		$content = rmWhiteSpace($content); //Rm any whitespace from content

		//Continue while there is still a subtag to be parsed.
		while (strlen($content) > 1) {
			$tag = getBetweenTags ($content, "<", ">"); //Samples the fist tag in the content

			switch ($tag) {
				case "topic" 		: 	$topic = getBetweenTags ($content, "<topic>", "</topic>");
										$content = removeTags ($content, "<topic>".$topic."</topic>");
										echo "<h3>".$topic."</h3>"; //Displays the topic heading for the content.
										break;
				case "par"			: 	$par = getBetweenTags ($content, "<par>", "</par>");
										$content = removeTags ($content, "<par>".$par."</par>");
										parsePar ($par); //Parse and displays a paragraph
										break;
				case "image"		: 	$image = getBetweenTags ($content, "<image>", "</image>");
										$content = removeTags ($content, "<image>".$image."</image>");
										parseImage ($image);//Parse and dispaly the image. Gets image from database.
										break;
				case "def"			: 	$def = getBetweenTags ($content, "<def>", "</def>");
										$content = removeTags ($content, "<def>".$def."</def>");
										parseDef ($def); //Parse and displays a definition
										break;
				case "list"			: 	$list = getBetweenTags ($content, "<list>", "</list>");
										$content = removeTags ($content, "<list>".$list."</list>");
										parseList ($list); //Parse and displays a list.
										break;

				case "codeExample" 	: 	$codeExample = getBetweenTags ($content, "<codeExample>", "</codeExample>");
										$content = removeTags ($content, "<codeExample>".$codeExample."</codeExample>");
										echo "<code>".$codeExample."</code>"; //Dispalys any code segments
										break;
				default 		:
			}
		} //end of while
	}

	/*
		Parse a lesson eml document.
		Goes through the document and parse each subtags.
		Removes parsed subtags after outputing to screen.
		Displays the result to screen.
	*/
	function parseLesson ($eml) {
		$eml = rmWhiteSpace ($eml); //Removes any whitespace in the eml document.
		$eml = getBetweenTags ($eml, "<lesson>", "</lesson>"); //Gets the content between the lesson tag
		
		$title = getBetweenTags ($eml, "<title>", "</title>"); //Get content between title tag
		echo "<h1>".$title."</h1>";
		$eml = removeTags ($eml, "<title>".$title."</title>"); //Removes subtag and content

		$subtitle = getBetweenTags ($eml, "<subTitle>", "</subTitle>");
		echo "<h2>".$subtitle."</h2>";
		$eml = removeTags ($eml, "<subTitle>".$subtitle."</subTitle>");

		//Continue while there is still a subtag to be parsed.
		while ((strlen ($eml) > 1)) {
			$tag = getBetweenTags ($eml, "<", ">");
			
			switch ($tag) {
				case "content" 	:	$content = getBetweenTags ($eml, "<content>", "</content>");
									$eml = removeTags ($eml, "<content>".$content."</content>");
									parseContent ($content); // Parse content and it's subtags
									break;
				case "image" 	: 	$image = getBetweenTags ($eml, "<image>", "</image>");
									$eml = removeTags ($eml, "<image>".$image."</image>");
									parseImage ($image); // Parse image and it's subtags. Gets Image from database.
									break;
				case "intro"	:	$intro = getBetweenTags ($eml, "<intro>", "</intro>");
									$eml = removeTags ($eml, "<intro>".$intro."</intro>");
									parseIntro ($intro); //Parse and dispaly introduction
									break;
				case "list" 	: 	$list = getBetweenTags ($eml, "<list>", "</list>");
									$eml = removeTags ($eml, "<list>".$list."</list>");
									parseList ($list); // Parse list and it's subtags
									break;
			}
		} // end of while
	}

	/*
		Parse a quiz eml document.
		Goes through the document and parse each subtags.
		Removes parsed subtags after outputing to screen.
		Displays the result to screen.
	*/
	function parseQuiz ($eml, $ans) {
		$eml = rmWhiteSpace ($eml); //Removes any whitespace from document
		$eml = getBetweenTags ($eml, "<quiz>", "</quiz>"); //Get content between quiz tag

		//parse and display title of document
		$title = getBetweenTags ($eml, "<title>", "</title>");
		echo "<h1>".$title."</h1>";
		$eml = removeTags ($eml, "<title>".$title."</title>");

		//parse and display quiz instructions
		$instruction = getBetweenTags ($eml, "<instruction>", "</instruction>");
		echo "<div class='quizInstruction'>$instruction</div>";
		$eml = removeTags ($eml, "<instruction>".$instruction."</instruction>");

		//Use form to contain multiple choice question and options
		echo "<div class='quizFormCon'>";
		echo "<form class='quizForm' method='POST'>";

		$numberQuestion = 0; //Used to help create the id for the questions
		//Continue while there is still a question subtag to be parsed.
		while (strlen ($eml) > 1) {
			$questionName = "question".$numberQuestion; //Create id for the question

			//Parsing a question
			$question = getBetweenTags ($eml, "<question>", "</question>");
			$eml = removeTags ($eml, "<question>".$question."</question>");
			echo "<div class='questionCon'>";

			$temp = ($numberQuestion+1).")"; //Display question number on screen
			$ask = getBetweenTags ($question, "<ask>", "</ask>");
			$question = removeTags ($question, "<ask>".$ask."</ask>");
			echo "<label>$temp $ask</label>";
			echo "<div>";
			echo "<select name='$questionName' id='$questionName'>";

			//Parse the four multiple choice options
			for ($i = 0; $i < 4; $i++) {
				$opt = "";

				//Used to create the value for the option
				switch ($i) {
					case 0	:	$opt = "A) ";
								break;
					case 1	:	$opt = "B) ";
								break;
					case 2	:	$opt = "C) ";
								break;
					case 3	:	$opt = "D) ";
								break;
				}

				$choice = getBetweenTags ($question, "<choice>", "</choice>");
				$question = removeTags ($question, "<choice>".$choice."</choice>");

				echo "<option value='$i'>$opt $choice</option>";
			}
			$answer = getBetweenTags ($question, "<answer>", "</answer>");
			$question = removeTags ($question, "<answer>".$answer."</answer>");
			$ans[$numberQuestion] = $answer; //stores the answer. Used to tally total score.

			echo "</select>";
			echo "</div>";
			echo "<div class='quizAnswer'>$answer</div>"; //Displays the answer to the question
			echo "</div>";
			$numberQuestion++;
		}
		
		echo "<input name='submitQuiz' type='submit' value='Submit'/>";
		echo "</form>";
		echo "</div>";
		return $ans;
	}

	// Used to convert the answer array to numbers.
	// Answer array then compared to form's answers value.
	function convertAnswer ($ans) {
		for ($i = 0; $i < count($ans); $i++) {
			switch ($ans[$i]) {
				case "A"		: 	$ans[$i] = "0"; 
									break;
				case "B"		:	$ans[$i] = "1"; 
									break;
				case "C"		:	$ans[$i] = "2"; 
									break;
				case "D"	: 		$ans[$i] = "3"; 
									break;
			}
		}
		return $ans;
	}

	/*
		Tally the number of correct marks by
		comparing form's answer value to ans array.
	*/
	function getScore ($ans) {
		$score = 0;
		for ($i = 0; $i < 10; $i++) {
			$id = "question".$i;

			if ($_POST[$id] == $ans[$i]) {
				$score++;
			}
		}
		return (($score / 10) *100);
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="../../../shared/tma2.css" />
		<script src=""></script>
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
		<div class="emlMenu">
			<a href="./enrolled.php"><img src="../../../shared/icons/return.png" alt="back"></a>
		</div>
		<div id="emlStaging" class="emlStagingCon">
			<?php
				//Check for id of eml document.
				if (isset ($_SESSION["emlID"])) {
					$eml = getEml ($conn, $_SESSION["emlID"]);
		
				//Checks for lesson or quiz eml document.
				if (strpos ($eml, "<lesson>") !== false) {
					parseLesson ($eml);
				}
				else if (strpos ($eml, "<quiz>") !== false) {
					$ans = parseQuiz ($eml, $ans);
				}
				else {
					echo "Unrecognizable format detected!";
				}
			}
			?>
			<div id="quizResultStaging">
				<?php
					//Occurs when the form posts to itself
					if ($_SERVER["REQUEST_METHOD"] ==  "POST") {
						if (isset($_POST["submitQuiz"])) {
							$ans = convertAnswer ($ans); //Tally's the answers
							$score = getScore ($ans);

							echo "<style>div.quizAnswer{display: block;}</style>";
							echo "Final Score: ". $score . "%";
						}
					}
				?>
			</div>
		</div>
	</body>
</html>
