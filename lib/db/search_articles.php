<?php
function search_articles($title_query = "", $md_content_query = "",
	$category_id = NULL, $id = NULL, $offset = 0, $limit = NULL,
	$status = "public,draft,archive,private")  {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db/conn.php';
	$conn = makeConnection();
	if($conn->connect_error) {
		$result = array("result" => "error", "reason" => "Internal error.");
		return $result;
	}
	$conn->query("USE site_content");
	if(is_null($id)) {
		$MAX_STATUS_NUMBER = 4;
		$title_query = '%' . $title_query . '%';
		$md_content_query = '%' . $md_content_query . '%';
		#TODO: Add category_id
		$query_raw = "SELECT * FROM articles WHERE title LIKE ? AND md_content LIKE ? AND (status in (?,?,?,?))" . (is_null($category_id) ? " OR category_id = ? " : " AND category_id = ? ") . "LIMIT ? OFFSET ?";
		$stmt = $conn->prepare($query_raw);
		$types = is_null($category_id) ? "sssssssii" : "ssssssiii";
		$category_id = is_null($category_id) ? 'category_id' : $category_id;
		$offset = is_null($limit) ? 0 : $offset;
		$limit = is_null($limit) ? 9999999 : $limit;
		$statuses = array_pad(explode(',', $status), 4 , '');
		$stmt->bind_param($types, $title_query, $md_content_query, 
			$statuses[0], $statuses[1], $statuses[2], $statuses[3],
			$category_id, $limit, $offset
		);
	}
	else {
		$stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
		$stmt->bind_param("i", $id);
	}
	if(!$stmt->execute()) {
		return array("result" => "error");
	}
	$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	return array("result" => "success", "data" => $result);
}
?>
