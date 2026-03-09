<?php
	include $_SERVER['DOCUMENT_ROOT'] . '/auth/auth.php';
	if(!tryAuth()) {
		header("Location: /login.php");
		die();
	}
	include $_SERVER['DOCUMENT_ROOT'] . '/lib/db/makedb.php';
?>
<head>
	<?php include './lib/common/head.php'; ?>
	<link rel="stylesheet" href="/css/panel.css">
</head>
<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/common/config.php"; ?>
<div class="sidebar-layout">
	<div class="sidebar">
		<h1><?php echo (new Config())->get("metadata", "blog_name");?></h1>
<?php
$menu = array(
	array(
		"category" => "Content managment",
		"items" => array(
			array("id" => "articles", "text" => "📰 articles"),
			array("id" => "categories", "text" => "🧰 categories"),
			array("id" => "media", "text" => "🖼️ media")
		)
	),
	array(
		"category" => "Appearance",
		"items" => array(
			array("id" => "style", "text" => "🎨 style"),
			array("id" => "layout", "text" => "🔨 layout"),
		)
	),
	array(
		"category" => "Settings",
		"items" => array(
			array("id" => "general", "text" => "🌐 general"),
			array("id" => "editor", "text" => "✏️ editor"),
			array("id" => "security", "text" => "🚨 security"),
		)
	),
	array(
		"category" => "Other",
		"items" => array(
			array("id" => "about", "text" => "ℹ️ about"),
		)
	)
);

$activeItem = "";

if(isset($_GET["item"])) {
	$activeItem = $_GET["item"];
}

foreach ($menu as $section) {
	echo '<b>' . $section["category"] . '</b>';
	foreach($section["items"] as $item) {
		$a =  '<a id="' . $item["id"] . '" href="panel.php?item=' . $item["id"] . '"';
		if($item["id"] === $activeItem) {
			$a = $a . ' class = "sidebar-item-selected"';
		}
		$a = $a . ">" . $item["text"] . '</a>';
		echo $a;
	}
}
?>
	</div>
	<div class="item-container">
<?php
if(isset($_GET["item"])) {
	$menuFile = $_SERVER['DOCUMENT_ROOT'] . "/lib/panel-menu/" . $_GET["item"] . '.php';
	if(file_exists($menuFile)) {
		include $menuFile;
	} else {
		echo "<b>Warning</b>! Menu item <i>" . $_GET["item"] . "</i> not implemented.";
	}
}
?>
	</div>
</div>

