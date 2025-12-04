<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/auth/auth.php';

if(!tryAuth()) {
	header("Location: /login.php");
	die();
}

if(!isset($_GET['id'])) {
	include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db/new_article.php';
	$result = new_article();
	header('Location: ' . '/editor.php?id=' . $result['article_id']);
	die();
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db/get_article.php';
$result = get_article($_GET['id']);
if($result['result'] != 'success') {
	echo "<b>Error</b><br>Reason: " . $result['reason'];
	die();
}
$article = $result['data'];
unset($result);
?>

<head>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/lib/common/head.php'; ?>
	<link rel="stylesheet" href="/css/editor.css">
	<script src="/js/parser.js" type="text/javascript"></script>
</head>
<body>
	<div class="page-content">
		<div class="nav">
			<a href="/panel.php?item=articles">back</a>
			<b>Editor</b>
			<i id="article-title" class="nav-article-title"><?php echo $article['title'] ?></i>
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
		<div id="tabs">
			<div class="full-vertical-center">
				<div class="toolbar">
					toolbar placeholder
				</div>
				<div class="editor-responsive">
					<textarea id="editor-field"><?php echo $article['md_content'] ?></textarea>
					<div class="preview">
						<article id="editor-preview">
						</article>
					</div>
				</div>
				<div class="toolbar">
					<span id="changes-status">✅ all changes saved</span>
				</div>
			</div>
			<div class="full-vertical-center">
				<div class="preview-full">
					<article id="editor-preview-full">
					</article>
				</div>
			</div>
			<div class="full-vertical-center publish-page">
				<form id="publish-form">
					<h1>Metadata</h1>
					<input name="id" style="display:none" value="<?php echo $article['id'] ?>"></input>
					<b>Title</b>
					<input type="text" name="title" value="<?php echo $article['title'] ?>"placeholder="Enter title...">
					<b>Description</b>
					<input type="text" name="description" value="<?php echo $article['description'] ?>" placeholder="Short description...">
					<b>Tags</b>
					<input type="text" name="tags" value="<?php echo $article['tags'] ?>"placeholder="history, science...">
					<b>Title image</b>
					<div class="img-select-box">
<?php
$img_files = scandir($_SERVER["DOCUMENT_ROOT"] . "/media");
$output = "";
if(count($img_files) <= 2) {
	echo "<p>There are no pictures uploaded. " . 
		"Go to <a target=\"_blank\" rel=\"noopener " . 
		"noreferrer\" href=\"/panel.php?item=media\">media</a> page.</p>";
}
foreach($img_files as $filename) {
	if(is_dir($_SERVER["DOCUMENT_ROOT"] . "/media/" . $filename)) {
		continue;
	}
	$checked = "";
	$is_chosen = "/media/" . $filename == $article["title_img_url"];
	if($is_chosen) {
		$checked = "checked";
	}
	$append = "";
	$append = '<input id="' . $filename . '" type="radio" name="title_img_url" value="/media/' .
	   	$filename . '" ' . $checked . '/>';
	$append = $append . '<label for="' . $filename . '"' . ' style="background: url(\'/media/' . $filename  . '\'); background-size: cover;">' . $filename . '</label>';
	if($is_chosen) {
		$output = $append . $output;
	} else {
		$output = $output . $append;
	}
}
echo $output;
?>
					</div>
					<b>Category</b>
					<select name="category_id">
						<?php 
						include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/db/get_categories.php';
						$categories = get_categories();
						if($categories) {
							foreach($categories as $cat) {
								$html =  '<option';
								if($cat['category_id'] == $article['category_id']) {
									$html = $html . ' selected';
								}
								$html = $html .	' value="' .  $cat['category_id'] . '">' .
								   	$cat['category_name'] . '</option>';
								echo $html;
							}
						}
						?>
					</select>
					<b>Status</b>
					<select name="status">
						<?php
						$status_values = ['public', 'draft', 'archive'];
						foreach($status_values as $value) {
							$html = '<option';
							if($value == $article['status']) {
								$html = $html . ' selected';
							}
							$html = $html . ' value="' . $value . '">' . $value . '</option>';
							echo $html;
						}
						?>
					</select>
					<button type="button" onclick="updateArticleMetadata()">Save</button>
				</form>
			</div>
		</div>
	</div>
	<script>var articleId = <?php echo $article['id'] ?>; let title = "<?php echo $article['title'] ?>";</script>
	<script src="/js/editor.js" type="text/javascript"></script>
	<script>updatePreview()</script>
</body>
