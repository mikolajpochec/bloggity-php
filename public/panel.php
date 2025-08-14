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
	<link rel="stylesheet" href="/css/panel.css">
</head>

<div class="sidebar-layout">
	<div class="sidebar">
		<h1>Blog name</h1>
<?php
$menu = array(
	array(
		"category" => "Content managment",
		"items" => array(
			array("id" => "articles", "text" => "📰 articles"),
			array("id" => "categories", "text" => "🧰 categories")
		)
	),
	array(
		"category" => "Appearance",
		"items" => array(
			array("id" => "styles", "text" => "🎨 styles"),
			array("id" => "layout", "text" => "🔨 layout"),
		)
	),
	array(
		"category" => "Settings",
		"items" => array(
			array("id" => "general", "text" => "🌐 general"),
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
	$menuFile = "../lib/panel-menu/" . $_GET["item"] . '.php';
	if(file_exists($menuFile)) {
		include $menuFile;
	} else {
		echo "<b>Warning</b>! Menu item <i>" . $_GET["item"] . "</i> not implemented.";
	}
}
?>
	</div>
</div>

