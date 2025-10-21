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
		</div>
		<a href="/editor.php">New article</a>
	</form>
</div>
<script src="/js/articles.js" type="text/javascript"></script>
