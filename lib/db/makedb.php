<?php
$env = parse_ini_file("../.env");
$host = $env["DB_HOST"];
$port = $env["DB_PORT"];
$user = $env["DB_USERNAME"];
$pass = $env["DB_PASSWORD"];

$conn = new mysqli($host . ":" . $port, $user, $pass);
if($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

# Create DB
$conn->query("
	CREATE DATABASE IF NOT EXISTS site_content 
	CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->query("USE site_content");
$conn->query("
	CREATE TABLE IF NOT EXISTS categories (
		catergory_id INT AUTO_INCREMENT PRIMARY KEY,
		category_name VARCHAR(128)
	)");

$conn->query("
	CREATE TABLE IF NOT EXISTS articles (
		id INT AUTO_INCREMENT PRIMARY KEY,
		title VARCHAR(512) NOT NULL,
		content MEDIUMTEXT NOT NULL, 
		tags VARCHAR(512) NOT NULL,
		catergory_id INT,
		status ENUM('public', 'draft', 'archive', 'private') NOT NULL DEFAULT 'draft',
		FOREIGN KEY (catergory_id) REFERENCES categories(catergory_id))");

$conn->close();
?>
