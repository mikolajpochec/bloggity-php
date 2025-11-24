<?php
function get_category_name($category_id) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db/conn.php';
	$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/.env");
	$conn = makeConnection();
	$conn->query("USE " . $env["DB_NAME"]);
	$stmt = $conn->prepare("SELECT * FROM categories WHERE category_id = ?");
	$stmt->bind_param("i", $category_id);
	$stmt->execute();
	$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["category_name"];
	if($result) {
		return $result;
	}
	return null;
}
?>
