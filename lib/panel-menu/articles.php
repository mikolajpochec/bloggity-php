<div class="column-container">
	<form class="vertical-container">
		<b>Filter</b>
		<input id="search-field" name="" type="text" placeholder="Search..."/>
		<div class="row-container gaps">
			<div class="row-container">
			<input checked="checked" id="chck-published" type="checkbox" data-status="public"/>
			<label for="chck-published">Published</label>
			</div>
			<div class="row-container">
			<input checked="checked" id="chck-drafts" type="checkbox" data-status="draft"/>
			<label for="chck-drafts">Drafts</label>
			</div>
			<div class="row-container">
			<input checked="checked" id="chck-archivized" type="checkbox" data-status="archive"/>
			<label for="chck-archivized">Archivized</label>
			</div>
			<div class="row-container">
			<input checked="checked" id="chck-private" type="checkbox" data-status="private"/>
			<label for="chck-private">Private</label>
			</div>
		</div>
		<div id="articles-preview-container" class="scrollable-list articles-list-preview">
<?php
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/search_articles.php";
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/get_category_name.php";
	$articles = search_articles(status: "public");
	foreach($articles["data"] as $article) {
		echo '<div class="elevated panel">';
		echo '<b>' . $article["title"] . '</b>';
		echo '<p><i>' . $article["description"] . '</i></p>';
		if(!is_null($article["category_id"])) {
			$category_name = get_category_name($article["category_id"]);
			echo '<p><i class="category">' . $category_name  . '</i></p>';
		}
		echo '<div class="scrollable-horizontal" style="gap: var(--global-content-padding);">';
		echo '<a href="/editor.php?id=' . $article["id"] . '">Edit</a>';
		echo '<a href="/?article_id=' . $article["id"] . '">View</a>';
		echo '<a>Delete</a>';
		echo '</div>';
		echo '</div>';
	}
?>
		</div>
		<a href="/editor.php">New article</a>
	</form>
</div>
