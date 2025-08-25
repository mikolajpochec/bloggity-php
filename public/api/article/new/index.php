<?php
include $_SERVER['DOCUMENT_ROOT'] . '/../auth/auth.php';
include $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/new_article.php';
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: GET");
#header('Access-Control-Allow-Origin: <origin>');
if(!tryAuth()) {
	$result = array("result" => "error", "reason" => "Unauthorized");
	echo json_encode($result);
	die();
}
$result = new_article();
echo json_encode($result);
?>
