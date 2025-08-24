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

include $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/conn.php';
$conn = makeConnection();
if($conn->connect_error) {
	$result = array("result" => "error", "reason" => "Internal error.");
	echo json_encode($result);
	die();
}
$conn->query("USE site_content");
$query_result = $conn->query("
INSERT INTO articles (title, mdcontent, tags, status)
VALUES ('New Article', '', '', 'draft')
");
if ($query_result === TRUE) {
	$last_id = $conn->insert_id;
	$result = array("result" => "success", "article_id" => $last_id);
	echo json_encode($result);
} else {
	$result = array("result" => "error", "reason" => "Internal error");
	echo json_encode($result);
}
$conn->close();
?>
