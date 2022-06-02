<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
		<h1>COMP 466: TMA2</h1>
		<hr>
		<h2>Intro</h2>
		<p>This repo is my submission for the second assignment of the course COMP 466: Advanced Technologies for Web-Based Systems. COMP 466 was an online course that covers advanced Web technologies that are widely used by IT professional in developing web-based systems and applications. Technologies covered include HTML5, CSS3, JavaScript, XML, Ajax, JSON, PHP, MySQL and ASP.NET. This assignment was designed to assess student's understanding of all the technologies mentioned except for ASP.NET.
		</p>
		<p>
		 The project has been divided into three parts:
		</p>
		<ol>
			<li>A homepage that contains the requirements, UML, and design specification of the other two parts.</li>
			<li>An online bookmarking service that keep tracks of user's bookmarks (save websites).</li>
			<li>An online learning management system that employs the use of a custom made EML language for marking up educational material.</li>
		</ol>
		<br>
		<h2>Languages Used</h2>
		<ul>
			<li>HTML5</li>
			<li>CSS3</li>
			<li>JavaScript</li>
			<li>XML</li>
			<li>PHP</li>
			<li>MySQL</li>
		</ul>
		<br>
		<h2>Set Up</h2>
		<p>This project requires the use of a http server with a PHP module installed on it and a MySQL server. All three of these components can be installed using XAMPP. To install:</p>
		<ol>
			<li>Download the xampp-installer.exe that matches your OS from the XAMPP home page.</li>
			<li>Run the executable and follow the prompts of the setup wizard.</li>
			<li>When reach "Select Components" part of the Setup wizard make sure to select: </li>
				<ul>
					<li>Apache</li>
					<li>MySQL</li>
					<li>PHP</li>
					<li>phpMyAdmin</li>
				</ul>
			<li>Finish the rest of the Setup and start the installation.</li>
		</ol>
		<p>Once the installation is complete start up the XAMPP Control Panel using admin privileges. Click the "Start" button next to Apache and MySQL to start the servers.</p>
		<br>
		<h2>Directory Setup</h2>
		<p>Once XAMPP is installed, open the /xampp/htdoc directory and create a new directory called "Testing". Unzip the project in this new directory. The unzip directory should be called "COMP466TMA2-master".</p>
		<br>
		<h2>Database</h2>
		<p>Each part of this project has their own SQL database. Access phpmyadmin and create two new databases called "tma2part1" and "tma2part2". Open up each database and import their respective .sql file. These files can be located under the directories \COMP466TMA2-master\part1 and \COMP466TMA2-master\part2. This will set up the required tables for the project.</p>
		<p>The project requires that a user/password be added to the phpmyadmin's user accounts database. Once the user has been added, locate part1 and part2 "connect.php" file and add in the new user name and password in order to access their database during runtime.</p>
		<br>
		<h2>How to Start</h2>
		<p>Start up Apache and MySQL in the XAMPP Control Panel. Open an internet browser and in the address bar type in "localhost/Testing/COMP466TMA2-master". Click on "tma2.htm" to start the project.</p>
		<br>
		<h2>PhpmyAdmin Tutorial</h2>
		<br>
		<h3>Accessing phpmyadmin</h3>
		<p>Start up Apache and MySQL in the XAMPP Control Panel using admin privileges. Open an internet browser and in the address bar type in "localhost". The XAMPP homepage should load. In the top right hand corner of this screen, click on "phpmyadmin".</p>
		<br>
		<h3>Adding a New User</h3>
		<p>Access phpmyadmin. Click on the "User accounts" tab. You should see a table consisting of five usernames. The username should be as followed: any, pma, root, root, root.  Click on "Add user account". Add in a new username and password. Under global privilege select "Data" and "Structure". Click on "Go". If you click on "User accounts" tab again, the table should contain the new username.</p>
		<br>
		<h3>Adding a new DataBase</h3>
		<p>Access phpmyadmin. In the left hand column of the screen under "phpmyadmin" there should be a list of databases. To create a new database, click on "new" which should be located at the top. For the database's name, type in the name of the database. . Make sure "utf8mb4_general_ci" is selected in the dropdown menu. Click on "Create".</p>
		<br>
		<h3>Importing Tables</h3>
		<p>Access phpmyadmin. In the left hand column under "phpmyadmin" there should be a list of databases. Click on the database that is to be modified. Click on the "Import" tab. Click on "Browse" and open the desired .sql file. In the bottom right hand screen, press "Go".</p>
		<br>
		<h2>References</h2>
		<ul>
			<li>COMP 466 (https://www.athabascau.ca/syllabi/comp/comp466.html)</li>
			<li>how to install xampp(https://www.ionos.ca/digitalguide/server/tools/xampp-tutorial-create-your-own-local-test-server/)</li>
			<li>phpmyadmin docs (https://docs.phpmyadmin.net/en/latest/)</li>
			<li>Create user (https://docs.phpmyadmin.net/en/latest/privileges.html)</li>
			<li>How to create a database (https://www.cloudways.com/blog/connect-mysql-with-php/)</li>
			<li>How to import a database (https://help.dreamhost.com/hc/en-us/articles/214395768-phpMyAdmin-How-to-import-a-database-or-table)</li>
		</ul>
	</body>
</html>
