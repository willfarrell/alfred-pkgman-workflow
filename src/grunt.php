<?php

//header ("Content-Type:text/xml");

$query = "contrib";
// ****************
//error_reporting(0);
require_once('cache.php');
require_once('workflows.php');

$cache = new Cache();
$w = new Workflows();
//$query = urlencode( "{query}" );

$pkgs = (array) $cache->get_db('grunt')->aaData;

function search($plugin, $query) {
	if (strpos($plugin->name, $query) !== false) {
		return true;
	} else if (strpos($plugin->description, $query) !== false) {
		return true;
	} 

	return false;
}

foreach($pkgs as $plugin) {
	if (search($plugin,  $query)) {
		$title = str_replace('grunt-', '', $plugin->name); // remove grunt- from title
	
		// add author to title
		if (isset($plugin->author)) {
			$title .= " by " . $plugin->author;
		}
		$url = 'https://npmjs.org/package/' . $plugin->name;
		
		//if (strpos($plugin->description, "DEPRECATED") !== false) { continue; } // skip DEPRECATED repos
		$w->result( $plugin->name, $url, $title, $plugin->ds, 'icon-cache/grunt.png' );
	}
}

if ( count( $w->results() ) == 0) {
	if($query) {
		$w->result( 'grunt', 'http://gruntjs.com/plugins/'.$query, 'No plugins were found that matched "'.$query.'"', 'Click to see the results for yourself', 'icon-cache/grunt.png' );
	}
	$w->result( 'grunt-www', 'http://gruntjs.com/', 'Go to the website', 'http://gruntjs.com', 'icon-cache/grunt.png' );
}

echo $w->toxml();
// ****************
?>