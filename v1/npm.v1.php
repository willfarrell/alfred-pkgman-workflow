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

$pkgs = $cache->get_query_regex('npm', $query, 'https://www.npmjs.org/search?q='.$query, '/<li class="search-result package">([\s\S]*?)<\/div>/i');

foreach($pkgs as $item) {
	preg_match('/<h2>(.*?)<\/h2>/i', $item, $matches);
	$title = strip_tags($matches[1]);

	preg_match_all('/<p class="description">([\s\S]*?)<\/p>([\s\S]*?)by([\s\S]*?)<\/span>/i', $item, $matches);

	$author = trim(strip_tags($matches[3][0]));
	$version = trim(strip_tags($matches[2][0]));
	$description = html_entity_decode(trim(strip_tags($matches[1][0])));

	$w->result( $title, 'https://www.npmjs.org/package/'.$title, $title.' ~ v'.$version.' by '.$author, $description, 'icon-cache/npm.png' );
}

if ( count( $w->results() ) == 0) {
	if($query) {
		$w->result( 'npm', 'https://www.npmjs.org/search?q='.$query, 'No packages were found that matched "'.$query.'"', 'Click to see the results for yourself', 'icon-cache/npm.png' );
	}
	$w->result( 'npm-www', 'https://www.npmjs.org/', 'Go to the website', 'https://www.npmjs.org', 'icon-cache/npm.png' );
}

echo $w->toxml();
// ****************
?>