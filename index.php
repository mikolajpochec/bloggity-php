<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="/css/global.css">
	</head>
	<body>
<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/common/config.php"; ?>
<div class="full-vertical-center">
	<a class="no-highlight" href="/"><h1><?php echo $config->get("metadata", "blog_name");?></h1></a>
	<div class="nav scrollable-horizontal">
		<?php
			include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/get_categories.php";
			$categories = get_categories();
			foreach($categories as $category) {
				echo "<a href=\"/?category_id=" . $category["category_id"] . "\">"
				   	. $category["category_name"] . "</a>";
			}
		?>
	</div>
</div>
<?php
if(isset($_GET["article_id"])) {
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/get_article.php";
	$response = get_article($_GET["article_id"]);
	if($response["result"] == "success") {
		if($response["data"]["status"] == "public") {
			echo "<div class='full-vertical-center'><article>";
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
