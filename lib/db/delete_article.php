<?php
function delete_article($id) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db/conn.php';
	$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/.env");
	$conn = makeConnection();
	$conn->query("USE " . $env["DB_NAME"]);
	$stmt = $conn->prepare("DELETE FROM articles WHERE id=?");
	$stmt->bind_param("i", $id);
	if($stmt->execute()) {
		return array("result" => "success");
	} else {
		return array("result" => "error");
	}
}
?>
