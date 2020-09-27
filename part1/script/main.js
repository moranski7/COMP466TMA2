var data;

/*
	Sets the events for the drop down menu.
*/
function start () {
	var addU = document.getElementById ("addURL");
	var deleteU = document.getElementById ("deleteURL");
	var editU = document.getElementById ("editURL");
	var logout = document.getElementById ("logoutOpt");

	addU.addEventListener ("click", loadMenuOpt, false);
	deleteU.addEventListener ("click", loadMenuOpt, false);
	editU.addEventListener ("click", loadMenuOpt, false);
	logout.addEventListener("click", confirmRedirect, false);
	displayMsg(data);
	console.log(data);
}

window.addEventListener("load", start, false);

/*
	Gets the file necessary for the particular menu option.
	Uses the element's id to determine which file.
*/
function getFile (id) {
	var file ="";

	switch (id) {
		case "addURL"		: 	file = "./add.php";
								break;
		case "deleteURL" 	: 	file = "./delete.php";
								break;
		case "editURL"		: 	file = "./edit.php";
								break;
		default			: 	console.log("Error inside getFile() switch.");
	}

	return file;
}

/*
	Uses Ajax to load the menu option onto the page without
	reloading the entire page.
*/
function loadMenuOpt () {
	var file = getFile (this.id);

	var xhttp = new XMLHttpRequest();
	document.getElementById("menuStagingArea").innerHTML = "";
	xhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("menuStagingArea").innerHTML = this.responseText;
		}
	};

	xhttp.open ("GET", file, true);
	xhttp.send();
}

/*
	Displays any result or errors from the
	php side of the database.
*/
function displayMsg(data) {
	var resultCon = document.getElementById("resultURL");
	resultCon.innerHTML = data;
}

function confirmRedirect() {
	var confirm = window.confirm ("You are about to logout.\n Are you sure you want to continue?");
	if (confirm) {
		 window.location.href = "./logout.php";
	}
}
