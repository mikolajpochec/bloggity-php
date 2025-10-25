<?php
class Config {
	function __construct() {
		if(!isset($GLOBALS["config"])) {
			$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/.env");
			$config_path = $env["CONFIG_FILE_PATH"];
			$GLOBALS["config_path"] = $config_path;
			$raw_config = file_get_contents($config_path);
			$decoded = json_decode($raw_config, true);
			$GLOBALS["configuration"] = $decoded;
		}
	}

	function get($category, $name) {
		return $GLOBALS["configuration"][$category][$name];
	}

	function set($category, $name, $value) {
		$GLOBALS["configuration"][$category][$name] = $value;
		$this->write();
	}

	private function write() {
		$json = json_encode($GLOBALS["configuration"]);
		$file = fopen($GLOBALS["config_path"], "w");
		$fwrite($file, $json);
		$fclose($file);
	}
}

$config = new Config();
?>
