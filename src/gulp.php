<?php

// ****************
//error_reporting(0);
require_once('cache.php');
require_once('workflows.php');

$cache = new Cache();
$w = new Workflows();
$query = urlencode( "{query}" );

$pkgs = (array) $cache->get_db('gulp')->results;

function search($plugin, $query) {
	if (strpos($plugin->name, $query) !== false) {
		return true;
	} else if (strpos($plugin->description, $query) !== false) {
		return true;
	} 

	return false;
}

foreach($pkgs as $plugin) {
	if (search($plugin, $query)) {
		$title = str_replace('gulp-', '', $plugin->name); // remove pulp- from title
	
		// add version to title
		if (isset($plugin->version)) {
			$title .= ' v'.$plugin->version;
		}
		// add author to title
		if (isset($plugin->author)) {
			$title .= " by " . $plugin->author;
		}
		
		//if (strpos($plugin->description, "DEPRECATED") !== false) { continue; } // skip DEPRECATED repos
		$w->result( $plugin->name, $plugin->homepage, $title, $plugin->description, 'icon-cache/gulp.png' );
	}
}


if ( count( $w->results() ) == 0) {
	if($query) {
		$w->result( 'gulp', 'http://gulpjs.com/plugins/#?q='.$query, 'No plugins were found that matched "'.$query.'"', 'Click to see the results for yourself', 'icon-cache/gulp.png' );
	}
	$w->result( 'gulp-www', 'http://gulpjs.com/', 'Go to the website', 'http://gulpjs.com', 'icon-cache/gulp.png' );
}

echo $w->toxml();
// ****************
?>