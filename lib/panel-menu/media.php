<?php
$viable_extensions = array("png", "jpg", "webp", "jpeg");

function filter_file_list($var) {
	global $viable_extensions;
	$separated = explode(".", $var);
	$ext = strtolower(array_pop($separated));
	return in_array($ext, $viable_extensions);
}

# Upload
$upload_status = "success";
if(count($_FILES) > 0) {
	$keep_original_name = strlen($_POST["filename"]) == 0;
	$ext = pathinfo($_POST["filename"], PATHINFO_EXTENSION);
	$upload_ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
	$upload_file = $_SERVER["DOCUMENT_ROOT"] . "/media/" . $_POST["filename"];
	if($keep_original_name) {
		$upload_file = $_SERVER["DOCUMENT_ROOT"] . "/media/" . basename($_FILES['file']['name']);
	}
	if($upload_ext != $ext) {
		$upload_status = "failure";
	}
	else if (!move_uploaded_file($_FILES["file"]["tmp_name"], $upload_file)) {
		$upload_status = "failure";
	}
	#header("Location: /panel.php?item=media&status=upload_" . $upload_status);
	#exit();
}

# Deletion
if(isset($_GET["delete_media"])) {
	$result = unlink("media/" . $_GET["delete_media"]);
	header("Location: /panel.php?item=media&status=delete_" . 
		($result ? "success" : "failure"));
	exit();
}


$file_list = array_filter(scandir("media"), "filter_file_list");
?>
<div class="column-container">
	<div class="vertical-container scrollable-list">
		<b>Add media</b>
		<form class="vertical-form" action="/panel.php?item=media"
				method="POST"
				enctype="multipart/form-data">

			<input type="file" name="file"/>
			<div style="display: flex; gap: var(--global-content-padding); align-items: center">
				<label for="filename">Save as:</label>
				<input style="flex-grow: 1;" 
					   type="text" 
					   name="filename" 
					   placeholder="Leave empty to use the original name"/>
				<input type="submit" value="Upload"/>
			</div>
		</form>
		<br>
		<b>Media</b>
		<div class="media-grid">
<?php
if(empty($file_list)) {
	echo "<p>There are no files.</p>";
}
else {
	foreach($file_list as $file) {
		echo "<div>";
		echo "<img src=\"media/" . $file . "\"/>";
		echo "<div class=\"media-bottom-bar\">";
		echo "<a href=\"/panel.php?item=media&delete_media=" . $file . "\">Delete</a>";
		echo "<span class=\"filename\">" . $file . "</span>";
		echo "</div>";
		echo "</div>";
	}
}
?>
		</div>
	</div>
</div>
