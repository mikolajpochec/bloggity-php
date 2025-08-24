<?php
include $_SERVER['DOCUMENT_ROOT'] . '/../auth/auth.php';
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: GET");
#header('Access-Control-Allow-Origin: <origin>');
if(!tryAuth()) {
	$result = array("result" => "error", "reason" => "Unauthorized");
	echo json_encode($result);
	die();
}
if(isset($_GET['id'])) {
	include $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/conn.php';
	$conn = makeConnection();
	$conn->query("USE site_content");
	if($conn->connect_error) {
		$result = array("result" => "error", "reason" => "Internal error.");
		echo json_encode($result);
		die();
	}
    $stmt = $conn->prepare("
        SELECT id, title, mdcontent, tags, category_id, status
        FROM articles
        WHERE id = ?
    ");
    $stmt->bind_param("i", $_GET['id']); 
    $stmt->execute();

    $result = $stmt->get_result();
    $result = $result->fetch_all(MYSQLI_ASSOC);
	if(is_null($result[0])) {
		$result = array("result" => "error", "reason" => "There is no article of id=" . $_GET['id']);
		echo json_encode($result);
	} else {
		echo json_encode(array("result" => "success", "data" => $result[0]));
	}

} else {
	$result = array("result" => "error", "reason" => "Parameter 'id' not specified.");
	echo json_encode($result);
}
?>
