<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/auth/auth.php';
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: POST");
if(!tryAuth()) {
	$result = array("result" => "error", "reason" => "Unauthorized");
	echo json_encode($result);
	die();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	$result = array("result" => "error", "reason" => "Method not allowed");
	echo json_encode($result);
	die();
}

$result = "";
if(isset($_POST["stylesheet_name"])) {
	$count = 0;
	$global_block = ":root{\n";
	$light_block = "media (prefers-color-scheme: light) {\n:root {\n";
	$dark_block = "media (prefers-color-scheme: dark) {\n:root {\n";

	foreach($_POST as $param => $value) {
		if(!str_starts_with($param, "--")) {
			continue;
		}
		$param = str_replace("|LIGHT", "", $param, $count);
		if($count > 0) {
			$light_block .= $param . ": " . $value . ";\n";
			continue;
		}
		$param = str_replace("|DARK", "", $param, $count);
		if($count > 0) {
			$dark_block .= $param . ": " . $value . ";\n";
			continue;
		}
		// In the other case it is just a global variable
		$global_block .= $param . ": " . $value . ";\n";
	}

	$template_file_path = $_SERVER["DOCUMENT_ROOT"]
		. "/res/global-style-template.css";
	$template_file = fopen($template_file_path, "r");
	$template = "";
	if(!$template_file) {
		$result = array(
			"result" => "error", 
			"reason" => "Could not open file '" . $template_file_path . "'"
		);
		die();
	}
	else {
		$template = fread($template_file, filesize($template_file_path));
	}

	$pattern1 = "@media\s*\(\s*prefers-color-scheme\s*:\s*light\s*\)\s*\{\s*:root\s*\{[\s\S]*?\}\s*\}@";
	$pattern2 = "@media\s*\(\s*prefers-color-scheme\s*:\s*dark\s*\)\s*\{\s*:root\s*\{[\s\S]*?\}\s*\}@";
	$pattern3 = "@:root\s*\{[\s\S]*?\}@";

	$output = preg_replace_callback($pattern1, fn($m) => $light_block . "}}", $template, 1);
	$output = preg_replace_callback($pattern2, fn($m) => $dark_block . "}}", $output, 1);
	$output = preg_replace_callback($pattern3, fn($m) => $global_block . "}", $output, 1);

	$output_file_path = $_SERVER["DOCUMENT_ROOT"] 
		. "/css/" . $_POST["stylesheet_name"];
	$output_file = fopen($output_file_path, "w");
	if(!$output_file) {
		$result = array(
			"result" => "error", 
			"reason" => "Could not open file '" . $output_file_path . "'"
		);
		echo json_encode($result);
		die();
	} else {
		if(fwrite($output_file, $output) === FALSE) {
			$result = array(
				"result" => "error", 
				"reason" => "Could not write to a file '" . $output_file_path . "'"
			);
			echo json_encode($result);
		}
		$result = array("result" => "success");
		echo json_encode($result);
	}
}
else {
	$result = array("result" => "error", "reason" => "Parameter 'stylesheet_name' is required.");
}
header("Location: /panel.php?item=style");
die();
?>
