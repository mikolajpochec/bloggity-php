<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: POST");
 if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	 $result = array("result" => "error", "reason" => "Method not allowed");
	 echo json_encode($result);
	 die();
 }

include $_SERVER['DOCUMENT_ROOT'] . '/../auth/auth.php';
if(!tryAuth()) {
	$result = array("result" => "error", "reason" => "Unauthorized");
	echo json_encode($result);
	die();
}

if((!isset($_POST['id'])) or count($_POST) < 2) {
	$result = array("result" => "error", "reason" => "Please specify 'id' and at least one other parameter");
	echo json_encode($result);
	die();
}
include $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/update_article.php';
echo json_encode(update_article($_POST['id'], $_POST));
?>
