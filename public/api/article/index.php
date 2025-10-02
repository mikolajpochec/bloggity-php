<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/../auth/auth.php';
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: GET");
#header('Access-Control-Allow-Origin: <origin>');
if(!tryAuth()) {
	$result = array("result" => "error", "reason" => "Unauthorized");
	echo json_encode($result);
	die();
}
$params = ['title', 'md-content', 'status', 'category-id', 'offset', 'results-limit'];
$should_perform_search = false;
foreach($params as $param) {
	if(isset($_GET[$param])) $should_perform_search = true;
}
if($should_perform_search) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/search_articles.php';
	echo json_encode(
		search_articles(
			title_query: isset($_GET['title']) ? $_GET['title'] : "",
			md_content_query: isset($_GET['md-content']) ? $_GET['md-content'] : "",
			status: isset($_GET['status']) ? $_GET['status'] : "",
			category_id: isset($_GET['category-id']) ? (int)$_GET['category-id'] : NULL,
			id: NULL,
			offset: isset($_GET['offset']) ? (int)$_GET['offset'] : NULL,
			limit: isset($_GET['limit']) ? (int)$_GET['results-limit'] : NULL
		)
	);
}
else if(isset($_GET['id'])) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/get_article.php';
	$result = get_article($_GET['id']);
	echo json_encode($result);
} else {
	$result = array("result" => "error", "reason" => "At least parameter 'id' needs to be specified.");
	echo json_encode($result);
}
?>
