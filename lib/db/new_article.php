<?php
function new_article() {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/conn.php';
	$conn = makeConnection();
	if($conn->connect_error) {
		$result = array("result" => "error", "reason" => "Internal error.");
		return ($result);
	}
	$conn->query("USE site_content");
	$query_result = $conn->query("
	INSERT INTO articles (title, md_content, md_content_latest_published, tags, status)
	VALUES ('New Article', '# New Article \n Start here', NULL, '', 'draft')
	");
	if ($query_result) {
		$last_id = $conn->insert_id;
		$result = array("result" => "success", "article_id" => $last_id);
		$conn->close();
		return ($result);
	} else {
		$result = array("result" => "error", "reason" => "Internal error");
		$conn->close();
		return ($result);
	}
}
?>
