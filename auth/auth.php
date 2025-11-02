<?php
define("ADMIN_HASH_PATH", $_SERVER['DOCUMENT_ROOT'] . "/auth/admin_hash");
define("ADMIN_SESSION_INFO_PATH",  $_SERVER['DOCUMENT_ROOT'] . "/auth/admin_session");
define("ADMIN_SESSION_DURATION_SECONDS", 60 * 60 * 2);

function updatePassword($new_password) {
	$file = fopen(ADMIN_HASH_PATH, "w+");
	$hash = password_hash($new_password, PASSWORD_DEFAULT);
	if(fwrite($file, $hash) === false) {
		return false;
	}
	return true;
}

function writeAdminSessionInfo($session_info) {
	$file = fopen(ADMIN_SESSION_INFO_PATH, "w+");
	$jsonEnc = json_encode($session_info);
	if(fwrite($file, $jsonEnc) === false) {
		return false;
	}
	return true;
}

function readAdminSessionInfo() {
	$data = file_get_contents(ADMIN_SESSION_INFO_PATH);
	if($data !== false) {
		return json_decode($data, false);
	}
	return false;
}

function destroyAdminSession() {
	if(!tryAuth()) return false;
	$info = array(
		"session_id" => "",
		"valid_until" => 0
	);
	writeAdminSessionInfo($info);
	session_destroy();
}

function verifyPassword($password) {
	$adminHash = trim(file_get_contents(ADMIN_HASH_PATH));
	$auth_result = password_verify($password, $adminHash);
	return $auth_result;
}

function tryAuth($password = null) {
	if(is_null($password)) {
		// Check the current session status
		$info = readAdminSessionInfo();
		if($info) {
			$is_valid = $info->session_id === session_id() && $info->valid_until >= time();
			if(!$is_valid) { session_destroy(); }
			return $is_valid;
		}
		session_destroy();
		return false;
	}
	else {
		// Try to create new session status
		session_regenerate_id(true);
		$adminHash = trim(file_get_contents(ADMIN_HASH_PATH));
		$auth_result = password_verify($password, $adminHash);
		if($auth_result) {
			$info = array(
				"session_id" => session_id(),
				"valid_until" => time() + ADMIN_SESSION_DURATION_SECONDS
			);
			writeAdminSessionInfo($info);
		}
		return $auth_result;
	}
}

ini_set("session.cookie_httponly", 1);
session_start();
?>
