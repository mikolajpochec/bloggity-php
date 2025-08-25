<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/../auth/auth.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/get_article.php';
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: GET");
#header('Access-Control-Allow-Origin: <origin>');
if(!tryAuth()) {
	$result = array("result" => "error", "reason" => "Unauthorized");
	echo json_encode($result);
	die();
}
if(isset($_GET['id'])) {
	$result = get_article($_GET['id']);
	echo json_encode($result);
} else {
	$result = array("result" => "error", "reason" => "Parameter 'id' not specified.");
	echo json_encode($result);
}
?>
