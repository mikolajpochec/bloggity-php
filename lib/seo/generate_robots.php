<?php
/* Generates robots.txt and saves it as root_dir/robots.txt */
function generate_robots_internal($protocol, $domain) {
	$sitemap_url = $protocol . "://" . $domain . "/sitemap.xml";
	$robots_path = $_SERVER["DOCUMENT_ROOT"] . "/robots.txt";
	$contents = <<<EOF
User-Agent: *
Disallow:

Sitemap: $sitemap_url
EOF;
	if(file_put_contents($robots_path, $contents) == false) {
		return array("result" => "error", "reason" => "File write error.");
	}
	return array("result" => "success");
}

/* Actual function call in external files */
function generate_robots() {
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/common/config.php";
	$config = new Config();
	$protocol = $config->get("web", "protocol");
	$domain = $config->get("web", "domain");
	generate_robots_internal($protocol, $domain);
}
?>
