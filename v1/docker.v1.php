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

$pkgs = $cache->get_query_regex('docker', $query, 'https://registry.hub.docker.com/search?q='.$query, '/(<a href="\/u\/.*"><div class="repo-list-item box">[\s\S]*?<\/a>)/i', 1); // requires parsing

//$count = 25;
foreach($pkgs as $pkg) {
	preg_match('/<a href="(.*?)">[\s\S]*?<h2>([\s\S]*?)<[\s\S]*<\/h2>([\s\S]*?)<\/div>/i', $pkg, $matches);
	$title = trim(preg_replace('/\s+/', '', $matches[2]));
	$url = 'https://registry.hub.docker.com'. trim(preg_replace('/\s+/', '', $matches[1]));
	$description = trim(preg_replace('/\s+/', ' ', $matches[3]));
	if (!$description || trim(preg_replace('/\s+/', '', $matches[3])) == '') {
		$description = $url;
	}

	preg_match('/<span class="timesince" data-time=".*">\s?([\s\S]*?)\s?<\/span>/i', $pkg, $matches);
	$updated = trim(preg_replace('/\s+/', ' ', $matches[1]));

	$w->result( $title, $url, $title.' ~ '.$updated, $description, 'icon-cache/docker.png' );

	//if (!--$count) { break; }
}

if ( count( $w->results() ) == 0) {
	if($query) {
		$w->result( 'docker', 'https://registry.hub.docker.com/search?q='.$query, 'No plugins were found that matched "'.$query.'"', 'Click to see the results for yourself', 'icon-cache/docker.png' );
	}
	$w->result( 'docker-www', 'http://www.docker.io/', 'Go to the website', 'http://www.docker.io', 'icon-cache/docker.png' );
}

echo $w->toxml();
// ****************
?>
