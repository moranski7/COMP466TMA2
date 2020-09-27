/*
	Set the events for the signup page.
*/

var errMsg;

function start() {
	var cancel = document.getElementById("returnMain");
	cancel.addEventListener("click", backToMain, false);
	displayErrMsg(errMsg);
}

window.addEventListener("load", start, false);

function backToMain () {
	window.location.href = "../part1.php";
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
	Used to display any errors from the php side of the 
	database.
*/
function displayErrMsg (errMsg) {
	var err = document.getElementById ("signUpErrors");
	err.innerHTML = errMsg;
}
