/*
	Used to set the events for the buttons.
*/

var errMsg;

function start() {
	var cancel = document.getElementById ("returnMain");
	cancel.addEventListener ("click", backToMain);
	displayErrMsg(errMsg);
}

window.addEventListener ("load", start, false);

function backToMain () {
	window.location.href = "../part1.php";
}

/*Displays any errors from the php side of the database.*/
function displayErrMsg (errMsg) {
	var err = document.getElementById ("loginResult");
	err.innerHTML = errMsg;
}
