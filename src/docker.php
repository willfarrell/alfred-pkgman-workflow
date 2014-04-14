<?php

//header ("Content-Type:text/xml");
//syslog(LOG_ERR, );

//$query = "h";
// ****************
error_reporting(0);
require_once('cache.php');
require_once('workflows.php');

$cache = new Cache();
$w = new Workflows();
$query = urlencode( "{query}" );

$pkgs = $cache->get_query_regex('docker', $query, 'https://index.docker.io/search?q='.$query, '/<div class="repo-list-item">([\s\S]*?)<hr class="repo-list-separator">/i', 1); // requires parsing

//$count = 25;
foreach($pkgs as $pkg) {
	preg_match('/<div class="repo-list-item-description"><a href=\'(.*?)\'><h2>(.*?)<\/h2><\/a>/i', $pkg, $matches);
	var_dump($pkg);var_dump($matches);
	$title = $matches[2];
	$url = 'https://index.docker.io'.$matches[1];
	
	preg_match('/<p>(.*?)<\/p>/i', $pkg, $matches);
	$description = $matches[1];
	if (!$description) {
		$description = $url;
	}
	
	if (stripos($pkg, 'Registered (and last updated)') !== false) {
		$official = '[Official]';
	} else {
		$official = '';
	}
	
	preg_match('/<span class="fixtime" utc-date="[\w-:+]*">[\s]*([\s\S]*?)[\s]*<\/span>/i', $pkg, $matches);
	$updated = $matches[1];
	
	$w->result( $title, $url, $title."\t".$official."\t".$updated, $description, 'icon-cache/docker.png' );
	
	//if (!--$count) { break; }
}

if ( count( $w->results() ) == 0) {
	if($query) {
		$w->result( 'docker', 'https://index.docker.io/search?q='.$query, 'No plugins were found that matched "'.$query.'"', 'Click to see the results for yourself', 'icon-cache/docker.png' );
	}
	$w->result( 'docker-www', 'http://www.docker.io/', 'Go to the website', 'http://www.docker.io', 'icon-cache/docker.png' );
}

echo $w->toxml();
// ****************
?>