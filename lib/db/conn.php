<?php
function makeConnection() {
	$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/.env");
	$host = $env["DB_HOST"];
	$port = $env["DB_PORT"];
	$user = $env["DB_USERNAME"];
	$pass = $env["DB_PASSWORD"];

	$conn = new mysqli($host . ":" . $port, $user, $pass);

	return $conn;
}
?>
