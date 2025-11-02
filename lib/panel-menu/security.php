<div class="column-container">
	<div class="vertical-container scrollable-list">
		<b>Change password</b>
		<form class="vertical-form" action="">
			<input type="hidden" name="item" value="security"></input>
			<input type="password" id="input-password-old" name="p-old" placeholder="Old password"></input>
			<input type="password" id="input-password-new" name="p-new" placeholder="New password"></input>
			<input type="password" id="input-password-re"  name="p-re" placeholder="Retype new password"></input>
<?php
if(isset($_GET["p-old"]) 
	&& isset($_GET["p-old"]) 
	&& isset($_GET["p-old"]))
{
	include_once $_SERVER["DOCUMENT_ROOT"] . "/auth/auth.php";
	if(empty($_GET["p-new"]) || empty($_GET["p-re"])) {
		echo '<label class="warning-text">';
		echo 'Empty password.';
		echo '</label>';
	}
	else if($_GET["p-new"] != $_GET["p-re"]) {
		echo '<label class="warning-text">';
		echo 'Passwords do not match.';
		echo '</label>';
	}
	else if(!verifyPassword($_GET["p-old"])) {
		echo '<label class="warning-text">';
		echo 'Incorrect old password.';
		echo '</label>';
	}
	else if(!updatePassword($_GET["p-new"])) {
		echo '<label class="warning-text">';
		echo 'Error.';
		echo '</label>';
	}
}
?>
			<button type="submit" style="margin: 0">Submit</button>
		</form>
	</div>
</div>
