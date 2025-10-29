<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: POST");

 if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	 $result = array("result" => "error", "reason" => "Method not allowed");
	 echo json_encode($result);
	 die();
 }

include $_SERVER['DOCUMENT_ROOT'] . '/auth/auth.php';
if(!tryAuth()) {
	$result = array("result" => "error", "reason" => "Unauthorized");
	echo json_encode($result);
	die();
}

if((!isset($_POST["category_id"]))) {
	$result = array("result" => "error", "reason" => "Please specify 'category_id'");
	echo json_encode($result);
	die();
}
include $_SERVER['DOCUMENT_ROOT'] . "/lib/db/delete_category.php";
echo json_encode(delete_category($_POST["category_id"]));
?>
