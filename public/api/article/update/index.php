<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: POST");
 if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	 $result = array("result" => "error", "reason" => "Method not allowed");
	 echo json_encode($result);
	 die();
 }

include $_SERVER['DOCUMENT_ROOT'] . '/../auth/auth.php';
if(!tryAuth()) {
	$result = array("result" => "error", "reason" => "Unauthorized");
	echo json_encode($result);
	die();
}

if((!isset($_POST['id'])) or count($_POST) < 2) {
	$result = array("result" => "error", "reason" => "Please specify 'id' and at least one other parameter");
	echo json_encode($result);
	die();
}

include $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/conn.php';
$conn = makeConnection();
$conn->query("USE site_content");
$stmt = $conn->prepare("
	SELECT id, title, mdcontent, tags, category_id, status
	FROM articles
	WHERE id = ?
	");
$stmt->bind_param("i", $_POST['id']); 
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_all(MYSQLI_ASSOC)[0];
foreach ($_POST as $key => $value) {
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
$stmt->execute();
?>
