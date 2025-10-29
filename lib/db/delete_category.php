<?php
function delete_category($id) {
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/conn.php";
	$env = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . "/.env");
	$conn = makeConnection();
	if($conn->connect_error) {
		$result = array("result" => "error", "reason" => "Internal error");
		return ($result);
	}
	$conn->query("USE " . $env["DB_NAME"]);

	// Remove relations
	$stmt = $conn->prepare("UPDATE articles SET category_id=null WHERE category_id=?");
	$stmt->bind_param("i", $id);
	if(!$stmt->execute()) {
		return array("result" => "error", "reason" => "SQL query failed. Cannot unset foreign keys in articles.");
	}

	$stmt = $conn->prepare("DELETE FROM categories WHERE category_id=?");
	$stmt->bind_param("i", $id);
	if(!$stmt->execute()) {
		return array("result" => "error", "reason" => "Main SQL query failed.");
	}
	return array("result" => "success");
}
?>
