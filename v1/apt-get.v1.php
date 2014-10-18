<?php

//header ("Content-Type:text/xml");
//syslog(LOG_ERR, "message to send to log");

//$query = "angular";
// ****************
//error_reporting(0);
require_once('cache.php');
require_once('workflows.php');

$cache = new Cache();
$w = new Workflows();
$query = urlencode( "{query}" );

$pkgs = $cache->get_query_regex('apt-get', $query, 'https://apps.ubuntu.com/cat/search/?q='.$query, '/<tr>([\s\S]*?)<\/tr>/i');


foreach($pkgs as $item) {
	preg_match('/<p>(.*?)<\/p>/i', $item, $matches);
	$name = trim(strip_tags($matches[1]));
	
	preg_match('/<h3>([\s\S]*?)<\/h3>/i', $item, $matches);
	$description = trim(strip_tags($matches[1]));

	$w->result( $name, 'https://apps.ubuntu.com/cat/applications/'.$name, $name, $description, 'icon-cache/apt-get.png' );
	//break;
}

if ( count( $w->results() ) == 0) {
	if($query) {
		$w->result( 'apt-get', 'https://apps.ubuntu.com/cat/search/?q='.$query, 'No packages were found that matched "'.$query.'"', 'Click to see the results for yourself', 'icon-cache/apt-get.png' );
	}
	$w->result( 'apt-get-www', 'https://apps.ubuntu.com/cat/', 'Go to the website', 'https://apps.ubuntu.com', 'icon-cache/apt-get.png' );
}

echo $w->toxml();
// ****************
?>