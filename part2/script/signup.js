var errMsg;

function start () {
	var back = document.getElementById ("returnMain");
	back.addEventListener ("click", backToMain, false);
	displayError (errMsg);
}

window.addEventListener("load", start, false);

/*
	Redirect back to introduction/login page.
*/
function backToMain () {
	window.location.href = "../part2.php";
}

/*
	Used to remove whitespace in password and username
	in the form.
*/
function trimSignUpForm(id) {
	input = document.getElementById(id);
	input.value = input.value.trim();
}

/*
	Display any errors or messages from the php
	side of the program.
*/
function displayError (errMsg) {
	var errCon = document.getElementById("signUpErrorsPart2");
	errCon.innerHTML = errMsg;
}
