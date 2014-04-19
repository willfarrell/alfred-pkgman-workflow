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

$pkgs = $cache->get_query_regex('puppet', $query, 'https://forge.puppetlabs.com/modules?utf-8=✓&sort=rank&q='.$query, '/<li class="clearfix ">([\s\S]*?)<\/li>/i');

foreach($pkgs as $item) {
	preg_match('/<h3>([\s\S]*?)<\/h3>/i', $item, $matches);
	$name = trim(strip_tags($matches[1]));
	
	preg_match('/<p>([\s\S]*?)<\/p>/i', $item, $matches);
	$description = trim(strip_tags($matches[1]));
	
	preg_match('/Version([\s\S]*?)<\/a>/i', $item, $matches);
	$version = trim(strip_tags($matches[1]));

	$w->result( $name, 'https://forge.puppetlabs.com/'.$name, $name.' ~ v'.$version, $description, 'icon-cache/puppet.png' );
	//break;
}

if ( count( $w->results() ) == 0) {
	if($query) {
		$w->result( 'puppet', 'https://apps.ubuntu.com/cat/search/?q='.$query, 'No modules were found that matched "'.$query.'"', 'Click to see the results for yourself', 'icon-cache/puppet.png' );
	}
	$w->result( 'puppet-www', 'https://forge.puppetlabs.com/', 'Go to the website', 'https://forge.puppetlabs.com', 'icon-cache/puppet.png' );
}

echo $w->toxml();
// ****************
?>