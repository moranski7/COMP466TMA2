<form action="main.php" method="post">
	<h3>Edit Bookmark</h3>
	<input type="submit" value="Edit"/>
	<div class="urlInputCon">
		<label for="editFromURL">ID of Old URL:</label>
		<input type="number" id="editFromURL" name="editFromURL" required/>
	</div>
	<div></div>
	<div class="urlInputCon">
		<label for="editToURL">New URL:</label>
		<input type="url" id="editToURL" name="editToURL" required/>
	</div>
</form>
