<?php
class Config {
	function __construct() {
		if(!isset($GLOBALS["configuration"])) {
			$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/.env");
			$config_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $env["CONFIG_FILE_PATH"];
			$GLOBALS["config_path"] = $config_path;
			$raw_config = file_get_contents($config_path);
			if ($raw_config === false) {
				throw new Exception("Config file reading error: " . json_last_error_msg());
			}
			$decoded = json_decode($raw_config, true);
			if ($decoded === null) {
				throw new Exception("Config file decoding error: " . json_last_error_msg());
			}
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
		fwrite($file, $json);
		fclose($file);
	}
}
?>
