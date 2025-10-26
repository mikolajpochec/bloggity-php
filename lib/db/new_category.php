<?php
function new_category($name) {
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/conn.php";
	$env = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . "/.env";
	$conn = makeConnection();
	if($conn->connect_error) {
		$result = array("result" => "error", "reason" => "Internal error");
		return ($result);
	}
	$conn->query("USE " . $env["DB_NAME"]);
	$stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
	$stmt->bind_param("s", $name);
	if(!$stmt->execute()) {
		return array("result" => "error", "reason" => "SQL query failed.");
	}
	return array("result" => "success");
}
?>
