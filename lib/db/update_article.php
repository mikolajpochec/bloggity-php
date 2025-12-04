<?php
function update_article($id, $values_array) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db/conn.php';
	$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/.env");
	$conn = makeConnection();
	$conn->query("USE " . $env["DB_NAME"]);

	$stmt = $conn->prepare("
		SELECT id, title, md_content, md_content_latest_published, tags, category_id, 
		status, html, original_time, last_update_time
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
 	if($article["status"] == "public") {
		$time = date("Y-m-d H:i:s");
		$article["last_update_time"] = $time;
		if(is_null($article["original_time"])) {
			$article["original_time"] = $time;
		}
	}
	$stmt = $conn->prepare("
		UPDATE articles
		SET title = ?, md_content = ?, tags = ?, category_id = ?, status = ?, html = ?,
		md_content_latest_published = ?, description = ?, title_img_url = ?,
		original_time = ?, last_update_time = ? WHERE id = ?
	");
	$stmt->bind_param("sssisssssssi", $article['title'], $article['md_content'],
		$article['tags'], $article['category_id'], $article['status'],
		$article['html'], $article['md_content_latest_published'], $article['description'],
		$article['title_img_url'], $article["original_time"], $article["last_update_time"], 
		$article['id']); 
	if($stmt->execute()) {
		return array("result" => "success");
	} else {
		return array("result" => "error");
	}
}
?>
