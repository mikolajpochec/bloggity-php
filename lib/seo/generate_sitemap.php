<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/db/search_articles.php";

function get_article_url_tag($article_id, $date_text, $protocol, $domain) {
	$loc = $protocol . "://" . $domain;
	$loc = $loc . "/?article_id=" . $article_id;
	$date = date_create($date_text);
	$date = date_format($date, "Y-m-d");
	return <<<EOF
	<url>
		<loc>$loc</loc>
		<lastmod>$date</lastmod>
		<changefreq>weekly</changefreq>
	</url>

EOF;
}

/* Generates sitemap and saves it as root_dir/sitemap.xml */
function generate_sitemap_internal($protocol, $domain) {
	$urls = "";

	$response = search_articles(status: "public");
	if($response["result"] != "success") {
		return array("result" => "error", "reason" => $response["reason"]);
	}

	foreach($response["data"] as $article) {
		$urls = $urls . get_article_url_tag(
			$article["id"],
			$article["last_update_time"],
			$protocol, $domain
		);
	}

	$url = $protocol . "://" . $domain;
	$date = date("Y-m-d");
	$contents = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>$url/</loc>
		<lastmod>$date</lastmod>
		<changefreq>hourly</changefreq>
	</url>
$urls
</urlset>
EOT;

	$sitemap_file_path = $_SERVER["DOCUMENT_ROOT"] . "/sitemap.xml";
	if(file_put_contents($sitemap_file_path, $contents) == false) {
		return array("result" => "error", "reason" => "File write error.");
	}

	return array("result" => "success");
}

/* Actual function call in an external file */
function generate_sitemap() {
	include_once $_SERVER["DOCUMENT_ROOT"] . "/lib/common/config.php";
	$config = new Config();
	$protocol = $config->get("web", "protocol");
	$domain = $config->get("web", "domain");
	generate_sitemap_internal($protocol, $domain);
}
?>
