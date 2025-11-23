<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="/css/global.css">
	</head>
	<body>
<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/common/config.php";
echo $config->get("metadata", "blog_name");

if(isset($_GET["article_id"])) {
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/get_article.php";
	$response = get_article($_GET["article_id"]);
	if($response["result"] == "success") {
		if($response["data"]["status"] == "public") {
			echo "<div class='full-vertical-center'><article>";
			echo '<h1 class="article-title">' . $response["data"]["title"] . '</h1>';
			echo $response["data"]["html"];
			echo "</article></div>";
		}
		else {
			echo "This article is hidden."; 
		}
	}
	else {
			echo "Cannot retrieve this article."; 
	}
}
?>
	</body>
</html>
