<?php

include '../lib/db/conn.php';
$conn = makeConnection();

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
		category_id INT AUTO_INCREMENT PRIMARY KEY,
		category_name VARCHAR(128)
	)");

$conn->query("
	CREATE TABLE IF NOT EXISTS articles (
		id INT AUTO_INCREMENT PRIMARY KEY,
		title VARCHAR(512) NOT NULL,
		md_content MEDIUMTEXT NOT NULL, 
		md_content_latest_published MEDIUMTEXT NULL DEFAULT NULL, 
		html LONGTEXT NULL DEFAULT NULL, 
		tags VARCHAR(512) NOT NULL,
		category_id INT,
		status ENUM('public', 'draft', 'archive', 'private') NOT NULL DEFAULT 'draft',
		FOREIGN KEY (category_id) REFERENCES categories(category_id))");

$conn->close();
?>
