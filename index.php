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
<div class="full-vertical-center">
	<div class="article-wide vertical-form">
<?php
if(isset($_GET["category_id"])) {
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/search_articles.php";
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/get_category_name.php";
	$articles = search_articles(category_id: $_GET["category_id"], status: "public");
	$category_name = get_category_name($_GET["category_id"]);
	echo "<p>Articles from category <b>" . $category_name . "</b></p>";
	foreach($articles["data"] as $article) {
		#echo "<a class=\"no-highlight\" href=\"/?article_id=" . $article["id"] . "\">";
		echo "<div class=\"panel\">";
		echo "<b>" . $article["title"] . "</b>";
		echo "<div><i>" . $article["description"] . "</i></div>";
		echo "<div class=\"scrollable-horizontal\">";
		foreach(explode(",", $article["tags"]) as $tag) {
			if(!empty(trim($tag))) {
				echo "<a href=\"?by-tag=" . $tag . "\" class=\"chip no-highlight\">" . $tag . "</a>";
			}
		}
		echo "</div>";
		echo "<a href=\"/?article_id=" . $article["id"] . "\">Read more...</a>"; 
		echo "</div>";
		#echo "</a>";
	}
}
?>
	</div>
</div>
<?php
if(isset($_GET["article_id"]) && !isset($_GET["category_id"])) {
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/get_article.php";
	$response = get_article($_GET["article_id"]);
	$article = $response["data"];
	if($response["result"] == "success") {
		if($article["status"] == "public") {
			echo "<div class='full-vertical-center'>";
			echo "<article>";
			echo $article["html"];
			echo "</article>";
			echo "<div class=\"date-text\">";
			echo "<i>Published: " . $article["original_time"] . "</i>";
			if($article["original_time"] != $article["last_update_time"]) {
				echo "<i>Updated: " . $article["last_update_time"] . "</i>";
			}
			echo "</div></div>";
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
