<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: GET");

include $_SERVER['DOCUMENT_ROOT'] . '/auth/auth.php';
if(!tryAuth()) {
	$result = array("result" => "error", "reason" => "Unauthorized");
	echo json_encode($result);
	die();
}
if(!isset($_GET["id"])) {
	$result = array("result" => "error", "reason" => "Please specify the 'id' parameter");
	echo json_encode($result);
	die();
}

include $_SERVER['DOCUMENT_ROOT'] . '/lib/db/delete_article.php';
echo json_encode(delete_article($_GET["id"]));
?>
