<?php
function get_article($id) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db/conn.php';
	$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/.env");
	$conn = makeConnection();
	$conn->query("USE " . $env["DB_NAME"]);
	if($conn->connect_error) {
		$result = array("result" => "error", "reason" => "Internal error.");
		return $result;
	}
    $stmt = $conn->prepare("
        SELECT * FROM articles WHERE id = ?
    ");
    $stmt->bind_param("i", $id); 
    $stmt->execute();

    $result = $stmt->get_result();
    $result = $result->fetch_all(MYSQLI_ASSOC);
	if(is_null($result[0])) {
		$result = array("result" => "error", "reason" => "There is no article of id=" . $id);
		return $result;
	} else {
		return array("result" => "success", "data" => $result[0]);
	}
}
?>
