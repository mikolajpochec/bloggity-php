<?php
function update_article($id, $values_array) {
	include $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/conn.php';
	$conn = makeConnection();
	$conn->query("USE site_content");
	$stmt = $conn->prepare("
		SELECT id, title, mdcontent, tags, category_id, status
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
		SET title = ?, mdcontent = ?, tags = ?, category_id = ?, status = ?
		WHERE id = ?
	");
	$stmt->bind_param("sssisi", $article['title'], $article['mdcontent'],
		$article['tags'], $article['category_id'], $article['status'], $article['id']); 
	if($stmt->execute()) {
		return array("result" => "success");
	} else {
		return array("result" => "error");
	}
}
?>
