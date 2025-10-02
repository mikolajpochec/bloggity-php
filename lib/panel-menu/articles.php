<div class="column-container">
	<form class="vertical-container">
		<b>Filter</b>
		<input name="" type="text" placeholder="Search..."/>
		<div class="row-container gaps">
			<div class="row-container">
			<input checked="checked" id="chck-published" type="checkbox"/>
			<label for="chck-published">Published</label>
			</div>
			<div class="row-container">
			<input checked="checked" id="chck-drafts" type="checkbox"/>
			<label for="chck-drafts">Drafts</label>
			</div>
			<div class="row-container">
			<input checked="checked" id="chck-archivized" type="checkbox"/>
			<label for="chck-archivized">Archivized</label>
			</div>
			<div class="row-container">
			<input checked="checked" id="chck-private" type="checkbox"/>
			<label for="chck-private">Private</label>
			</div>
		</div>
		<div class="scrollable-list articles-list-preview">
			<?php
			include_once $_SERVER['DOCUMENT_ROOT'] . '/../lib/db/search_articles.php';
			$result = search_articles(title_query: "map", category_id: 2);
			var_dump($result);
			?>
			<div class="elevated">
				<b>Test title</b>
				<p><i>Once upon a time. Lorem ipsum dolor sit amet...</i></p>
				<p><i class="category">category_name</i></p>
			</div>
			<div class="elevated">
				<b>Test title</b>
				<p><i>Once upon a time. Lorem ipsum dolor sit amet...</i></p>
				<p><i class="category">category_name</i></p>
			</div>
			<?php
			?>
		</div>
	</form>
</div>
