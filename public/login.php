<?php
include '../auth/auth.php';

$is_auth = false;

if(isset($_GET['logout'])) {
	destroyAdminSession();
}

if (isset($_POST['password'])) {
	$is_auth = tryAuth($_POST['password']);
} else {
	$is_auth = tryAuth();
}
?>

<!DOCTYPE html>
<html>
<head>
	<?php include '../common/head.php'; ?>
	<link rel="stylesheet" href="/css/login.css">
</head>
<body>
	<div class="page-content">
			<?php if ($is_auth): ?>
		<form class="vertical-form login-panel" action="/login.php?logout=true" method="POST">
			<p>Already logged in.</p>
			<input name="logout" style="display:none;"/>
			<input type="submit" value="Log out">
			<?php else: ?>
		<form class="vertical-form login-panel" action="/login.php" method="POST">
			<p><b>Administrator panel</b></p>
			<input name="password" type="password" placeholder="Enter password">
			<input type="submit" value="Submit">
			<p class="subtle-text">What if <a href="/404.php">I have forgotten my password</a>?</p>
			<?php endif ?>
		</form>
	</div>
</body>
</html>
