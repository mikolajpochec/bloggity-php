<?php
if(isset($_GET["new-category-name"]) && !empty($_GET["new-category-name"])) {
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/new_category.php";
	new_category($_GET["new-category-name"]);
	header("Location: /panel.php?item=categories");
}
?>
<div class="column-container">
	<div class="vertical-container scrollable-list">
		<b>Add a new category</b>
		<form class="vertical-form" style="gap: 0" method="/panel.php?item=categories">
    		<input name="item" type="hidden" value="categories"/>
			<input name="new-category-name" type="text" placeholder="Name"></input>
			<button type="submit">Add</button>
		</form>
		<b>Categories</b>
			<?php
			include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/get_categories.php";
			$categories = get_categories();
			if($categories) {
				foreach($categories as $category) {
					echo   '<div class="panel row-container elevated category-item" id="cat-panel-'
															. $category["category_id"] . '">
								<p>' . $category["category_name"] . '</p>
								<div class="flow-right row-container">
									<button class="button-not-safe"' .
										' onclick="deleteArticleAPI(' . $category['category_id'] . ')">
										Delete
									</button>
								</div>
							</div>';
				}
			} 
			else {
				echo "Could not load categories.";
			}
			?>
	</div>
</div>
<script src="/js/categories.js" type="text/javascript"></script>
