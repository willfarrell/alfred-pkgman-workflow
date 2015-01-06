<?php

/*
Bower

*/

// ****************

require_once('cache.php');

class Repo {
	
	private $id = 'bower';
	private $kind = 'components'; // for none found msg
	private $min_query_length = 1; // increase for slow DBs
	private $max_return = 25;
	
	private $cache;
	private $w;
	private $pkgs;
	
	function __construct() {
		
		$this->cache = new Cache();
		
		// get DB here if not dynamic search
		//$data = (array) $this->cache->get_db('.$this->id.');
	}
	
	// return id | url | pkgstr
	function makeArg($id, $url, $version) {
		return $id . "|" . $url . "|" . "\"$id\":\"$version\",";
	}
	
	/*function check($pkg, $query) {
		if (!$query) { return true; }
		if (strpos($pkg["name"], $query) !== false) {
			return true;
		} else if (strpos($pkg["description"], $query) !== false) {
			return true;
		} 
	
		return false;
	}*/
	
	function search($query) {
		if ( strlen($query) < $this->min_query_length) {
			if ( strlen($query) === 0 ) { return; }
			$this->cache->w->result(
				"{$this->id}-min",
				$query,
				"Minimum query length of {$this->min_query_length} not met.",
				"",
				"icon-cache/{$this->id}.png"
			);
			return;
		}
		
		$this->pkgs = $this->cache->get_query_json('bower', $query, 'https://bower.herokuapp.com/packages/search/'.$query);
		
		foreach($this->pkgs as $pkg) {
			$url = str_replace("git://", "https://", $pkg->url);
			$this->cache->w->result(
				$pkg->url,
				$this->makeArg($pkg->name, $url, "*"),
				$pkg->name, $url,
				"icon-cache/{$this->id}.png"
			);
			
			// only search till max return reached
			if ( count ( $this->cache->w->results() ) == $this->max_return ) {
				break;
			}
		}
		
		if ( count( $this->cache->w->results() ) == 0) {
			$this->cache->w->result(
				"{$this->id}-search",
				"http://sindresorhus.com/bower-components/#!/search/{$query}",
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		$this->cache->w->result(
			"{$this->id}-www",
			"http://bower.io/",
			"Go to the website",
			"http://bower.io",
			"icon-cache/{$this->id}.png"
		);
		
		return $this->cache->w->toxml();
	}

}

// ****************

/*$query = "leaflet";
$repo = new Repo();
$repo->search($query);
echo $repo->xml();*/

?>