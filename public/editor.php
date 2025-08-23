<?php
	include '../auth/auth.php';
if(!tryAuth()) {
	header("Location: /login.php");
	die();
}
include '../lib/db/makedb.php';
?>

<head>
	<?php include '../lib/common/head.php'; ?>
	<link rel="stylesheet" href="/css/editor.css">
	<script src="/js/parser.js" type="text/javascript"></script>
</head>
<body>
	<div class="page-content">
		<div class="nav">
			<a href="/panel.php?item=articles">back</a>
			<b>Editor</b>
			<i class="nav-article-title">Article Title</i>
			<div class="multiple-choice-container">
				<label for="toggle-edit">Edit
					<input checked id="toggle-edit" type="radio" name="tab"/>
				</label>
				<label id="toggle-preview-container" for="toggle-preview">Preview
					<input id="toggle-preview" type="radio" name="tab"/>
				</label>
				<label for="toggle-publish">Publish
					<input id="toggle-publish" type="radio" name="tab"/>
				</label>
			</div>
		</div>
		<div class="toolbar">
			toolbar placeholder
		</div>
		<div id="tabs">
			<div class="editor-responsive">
				<textarea id="editor-field"></textarea>
				<div class="preview">
					<article id="editor-preview">
					</article>
				</div>
			</div>
			<div class="full-vertical-center">
				<div class="preview-full">
					<article id="editor-preview-full">
					</article>
				</div>
			</div>
			<div>
				3
			</div>
		</div>
		<div class="toolbar">
			toolbar placeholder
		</div>
	</div>
	<script src="/js/editor.js" type="text/javascript"></script>
</body>
