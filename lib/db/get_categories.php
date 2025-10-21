<?php
function get_categories() {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db/conn.php';
	$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/.env");
	$conn = makeConnection();
	$conn->query("USE " . $env["DB_NAME"]);
	$result = $conn->query("SELECT * FROM categories");
	if($result) {
		return $result;
	}
	return null;
}
?>
