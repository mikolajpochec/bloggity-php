<?php
function get_categories() {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/conn.php';
	$conn = makeConnection();
	$conn->query("USE site_content");
	$result = $conn->query("SELECT * FROM categories");
	if($result) {
		return $result;
	}
	return null;
}
?>
