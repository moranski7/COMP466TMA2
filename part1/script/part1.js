/*
	Sets the events for the buttons on the welcome page.
*/

function start () {
	var signUp = document.getElementById ("signUp");
	var login = document.getElementById ("login");

	signUp.addEventListener ("click", loadSignUp, false);
	login.addEventListener ("click", loadLogin, false);
}


window.addEventListener("load", start, false);

function loadSignUp () {
	window.location.href = "./server/signup.php";
}

function loadLogin() {
	window.location.href = "./server/login.php";
}
