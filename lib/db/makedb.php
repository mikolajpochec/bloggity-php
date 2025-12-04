<?php

include $_SERVER['DOCUMENT_ROOT'] . '/lib/db/conn.php';
$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/.env");
$db_name = $env['DB_NAME'];
$conn = makeConnection();

if($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

# Create DB
$conn->query("
	CREATE DATABASE IF NOT EXISTS " . $db_name .
	" CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->query("USE " . $db_name);
$conn->query("
	CREATE TABLE IF NOT EXISTS categories (
		category_id INT AUTO_INCREMENT PRIMARY KEY,
		category_name VARCHAR(128)
	)");

$conn->query("
	CREATE TABLE IF NOT EXISTS articles (
		id INT AUTO_INCREMENT PRIMARY KEY,
		title VARCHAR(512) NOT NULL,
		description TEXT,
		md_content MEDIUMTEXT NOT NULL, 
		md_content_latest_published MEDIUMTEXT NULL DEFAULT NULL, 
		html LONGTEXT NULL DEFAULT NULL, 
		title_img_url VARCHAR(512),
		tags VARCHAR(512) NOT NULL,
		category_id INT,
		original_time DATETIME,
		last_update_time DATETIME,
		status ENUM('public', 'draft', 'archive', 'private') NOT NULL DEFAULT 'draft',
		FOREIGN KEY (category_id) REFERENCES categories(category_id))");

$conn->close();
?>
