<?php
function update_article($id, $values_array) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db/conn.php';
	$conn = makeConnection();
	$conn->query("USE site_content");
	$stmt = $conn->prepare("
		SELECT id, title, md_content, md_content_latest_published, tags, category_id, status, html
		FROM articles
		WHERE id = ?
	");
	$stmt->bind_param("i", $id); 
	$stmt->execute();
	$result = $stmt->get_result();
	$article = $result->fetch_all(MYSQLI_ASSOC)[0];
	foreach ($values_array as $key => $value) {
		if (!is_null($value)) {
			$article[$key] = $value;
		}
	}
	$stmt = $conn->prepare("
		UPDATE articles
		SET title = ?, md_content = ?, tags = ?, category_id = ?, status = ?, html = ?,
		md_content_latest_published = ? WHERE id = ?
	");
	$stmt->bind_param("sssisssi", $article['title'], $article['md_content'],
		$article['tags'], $article['category_id'], $article['status'],
	   	$article['html'], $article['md_content_latest_published'], $article['id']); 
	if($stmt->execute()) {
		return array("result" => "success");
	} else {
		return array("result" => "error");
	}
}
?>
