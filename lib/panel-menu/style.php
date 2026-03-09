<?php
enum CSS_PARSER_MODE 
{
	case GlobalProperties;
	case DarkModeColors;
	case LightModeColors;
}

function replace_mode_indicators($text) {
	$formatted = trim(str_replace("|LIGHT", "", $text));
	$formatted = trim(str_replace("|DARK", "", $formatted));
	return $formatted;
}

function create_text_param_input($name, $initial_value) {
	$formatted_name = trim(str_replace("-", " ", $name));
	echo '<div class="wide-text-action">';
	echo '<p>' . $formatted_name . '</p>';
	echo '<input style="flex-grow: 0.75;"' . 'name="' . $name . '" type="text" value="' . $initial_value . '">';
	echo '</div>';
}

function create_color_param_input($name, $initial_color) {
	$formatted_name = trim(str_replace("-", " ", $name));
	$formatted_name = replace_mode_indicators($formatted_name);
	echo '<div class="wide-text-action">';
	echo '<p>' . $formatted_name . '</p>';
	echo '<input name="' . $name . '" type="color" value="' . $initial_color . '">';
	echo '</div>';
}

$global_css_path = $_SERVER["DOCUMENT_ROOT"] . "/css/global.css";
$handle = fopen($global_css_path, "r");
$global_params = array();
$dark_mode_colors = array();
$light_mode_colors = array();
?>

<div class="column-container">
	<form action="/write_stylesheet.php" method="post" class="scrollable-list article-wide">
	<input type="hidden" name="stylesheet_name" value="global.css"/>
<?php
if(!$handle) {
	echo '<p class="warning-text">Error: could not open file "' .
		$global_css_path . '"!</p>';
} 
else {
	$mode = CSS_PARSER_MODE::GlobalProperties;
	while (($line = fgets($handle)) !== false) {
		$line = trim($line);
		if(strcasecmp($line, "@media (prefers-color-scheme: light) {") == 0) {
			$mode = CSS_PARSER_MODE::LightModeColors; continue;
		}
		if(strcasecmp($line, "@media (prefers-color-scheme: dark) {") == 0) {
			$mode = CSS_PARSER_MODE::DarkModeColors; continue;
		}
		$parts = explode(":", $line);
		$param_name = trim($parts[0]);
		$param_value = trim(
			str_replace(
				'"',
				'&quot;',
				rtrim($parts[1], "; "))
		);

		if(!str_starts_with($line, "--")) continue;

		switch($mode) {
		case CSS_PARSER_MODE::GlobalProperties:
			$global_params[$param_name] = $param_value;
			break;
		case CSS_PARSER_MODE::LightModeColors:
			$light_mode_colors[$param_name . '|LIGHT'] = $param_value;
			break;
		case CSS_PARSER_MODE::DarkModeColors:
			$dark_mode_colors[$param_name . '|DARK'] = $param_value;
			break;
		}
	}
	echo "<b>Global constants</b>";
	foreach($global_params as $param => $value) {
		create_text_param_input($param, $value);
	}
	echo "<hr><b>Light mode colors</b>";
	foreach($light_mode_colors as $param => $value) {
		create_color_param_input($param, $value);
	}
	echo "<hr><b>Dark mode colors</b>";
	foreach($dark_mode_colors as $param => $value) {
		create_color_param_input($param, $value);
	}
}
?>
<button>Save</button>
	</form>
</div>
