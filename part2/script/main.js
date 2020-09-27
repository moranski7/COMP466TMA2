var data; // Used to display errors/msgs. Don't touch.

function start () {
	var logout = document.getElementById ("logoutOpt");
	logout.addEventListener ("click", confirmRedirect, false);
	displayMsg (data);
}

window.addEventListener("load", start, false);

/*
	Adds a row to the 'enroll' table.
*/
function createOption (lessonId, lessonName, type, subject, description) {
	var row = document.createElement ("tr");
	var colOne = document.createElement ("td");
	var colTwo = document.createElement ("td");
	var colThree = document.createElement ("td");
	var colFour = document.createElement ("td");
	var colFive = document.createElement ("td");
	var colSix = document.createElement ("td");

	var input = document.createElement ("input");
	var colID = document.createTextNode (lessonId);
	var colName = document.createTextNode (lessonName);
	var colSubject = document.createTextNode (subject);
	var colType = document.createTextNode (type);
	var colDescrib = document.createTextNode (description);

	//Input is checkbox to allow multiple lessons to be selected at once.
	input.setAttribute ("type", "checkbox");
	input.setAttribute ("value", lessonId);
	input.setAttribute ("id", subject+lessonId);
	input.setAttribute ("name", subject+lessonId);
	colOne.appendChild (input);

	colTwo.appendChild (colID);
	colThree.appendChild (colName);
	colFour.appendChild (colSubject);
	colFive.appendChild (colType);
	colSix.appendChild (colDescrib);
	
	row.appendChild (colOne);
	row.appendChild (colTwo);
	row.appendChild (colThree);
	row.appendChild (colFour);
	row.appendChild (colFive);
	row.appendChild (colSix);

	var form = document.getElementById ("lessonStorage");
	form.appendChild(row);
}

/*
	Adds a row to the 'start lesson' table.
*/
function createAttendOpt (lessonId, lessonName, type, subject, description) {
	var row = document.createElement ("tr");
	var colOne = document.createElement ("td");
	var colTwo = document.createElement ("td");
	var colThree = document.createElement ("td");
	var colFour = document.createElement ("td");
	var colFive = document.createElement ("td");
	var colSix = document.createElement ("td");

	var input = document.createElement ("input");
	var colID = document.createTextNode (lessonId);
	var colName = document.createTextNode (lessonName);
	var colSubject = document.createTextNode (subject);
	var colType = document.createTextNode (type);
	var colDescrib = document.createTextNode (description);

	//Creates a radio button that allows lesson to be selected
	input.setAttribute ("type", "radio");
	input.setAttribute ("value", lessonId);
	input.setAttribute ("id", subject+lessonId);
	input.setAttribute ("name", "lessonEML");
	colOne.appendChild (input);

	colTwo.appendChild (colID);
	colThree.appendChild (colName);
	colFour.appendChild (colSubject);
	colFive.appendChild (colType);
	colSix.appendChild (colDescrib);

	row.appendChild (colOne);
	row.appendChild (colTwo);
	row.appendChild (colThree);
	row.appendChild (colFour);
	row.appendChild (colFive);
	row.appendChild (colSix);

	var form = document.getElementById ("registeredTable");
	form.appendChild(row);
}

/*
	Displays any result or errors from the
	php side of the database.
*/
function displayMsg(data) {
	var resultCon = document.getElementById("lessonDispalyResult");
	resultCon.innerHTML = data;
}

/*
	Confirms whatever user wants to log out or not.
*/
function confirmRedirect() {
	var confirm = window.confirm ("You are about to logout.\n Are you sure you want to continue?");
	if (confirm) {
		 window.location.href = "./logout.php";
	}
}
