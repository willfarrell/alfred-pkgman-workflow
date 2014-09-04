<?php

//header ("Content-Type:text/xml");

//$query = "gen";
// ****************
//error_reporting(0);
require_once('cache.php');
require_once('workflows.php');

$query = urlencode( "{query}" );

$cache = new Cache();
$w = new Workflows();

$pkgs = (array) $cache->get_db('yo');

function search($plugin, $query) {
	if (strpos($plugin->name, $query) !== false) {
		return true;
	} else if (strpos($plugin->description, $query) !== false) {
		return true;
	} 

	return false;
}

foreach($pkgs as $plugin) {
	if ($query && search($plugin, $query)) {
		$title = $plugin->name;
		
		// add author to title
		if (isset($plugin->owner)) {
			$title .= " by " . $plugin->owner;
		}
		
		$w->result( $plugin->name, $plugin->website, $title, $plugin->description, 'icon-cache/yo.png' );
	}
}


if ( count( $w->results() ) == 0) {
	if($query) {
		$w->result( 'yo', 'http://yeoman.io/community-generators.html?q='.$query, 'No generators were found that matched "'.$query.'"', 'Click to see the results for yourself', 'icon-cache/yo.png' );
	}
	$w->result( 'yo-www', 'http://yoeman.io/', 'Go to the website', 'http://yoeman.io', 'icon-cache/yo.png' );
}

echo $w->toxml();
// ****************
?>