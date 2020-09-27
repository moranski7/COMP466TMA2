var errMsg;

function start () {
	var register = document.getElementById ("registerUser");

	register.addEventListener ("click", goToRegister, false);
	displayMsg(errMsg);
}

window.addEventListener("load", start, false);

function goToRegister () {
	window.location.href = "./page/signup.php";
}

/*
	Displays any result or errors from the
	php side of the database.
*/
function displayMsg(data) {
	var resultCon = document.getElementById("loginResult");
	resultCon.innerHTML = data;
}
