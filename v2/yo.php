<?php

/*
yo

*/

// ****************

require_once('cache.php');

class Repo {
	
	private $id = 'yo';
	private $kind = 'generators'; // for none found msg
	private $min_query_length = 1; // increase for slow DBs
	private $max_return = 25;
	
	private $cache;
	private $w;
	private $pkgs;
	
	function __construct() {
		
		$this->cache = new Cache();
		
		// get DB here if not dynamic search
		$data = (array) $this->cache->get_db($this->id);
		$this->pkgs = $data;
	}
	
	// return id | url | pkgstr
	function makeArg($id, $url, $version) {
		return $id . "|" . $url . "|" . $id;//"\"$id\":\"$version\",";
	}
	
	function check($pkg, $query) {
		if (!$query) { return true; }
		if (strpos($pkg->name, $query) !== false) {
			return true;
		} else if (strpos($pkg->description, $query) !== false) {
			return true;
		}
	
		return false;
	}
	
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
		
		//$this->pkgs = $this->cache->get_query_json($this->id, $query, "_TEMPLATE_SEARCH_URL_{$query}");
		
		foreach($this->pkgs as $pkg) {
			
			// make params
			if ($this->check($pkg, $query)) {
				$title = $pkg->name;
				
				// add author to title
				if (isset($pkg->owner)) {
					$title .= " by " . $pkg->owner;
				}
				
				$this->cache->w->result(
					$pkg->name,
					$this->makeArg($pkg->name, $pkg->website, "*"),
					$title,
					$pkg->description,
					"icon-cache/{$this->id}.png"
				);
			}
			
			
			// only search till max return reached
			if ( count ( $this->cache->w->results() ) == $this->max_return ) {
				break;
			}
		}
		
		if ( count( $this->cache->w->results() ) == 0) {
			$this->cache->w->result(
				"{$this->id}-search",
				"http://yeoman.io/community-generators.html?q={$query}",
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		$this->cache->w->result(
			"{$this->id}-www",
			'http://yeoman.io/',
			'Go to the website',
			'http://yeoman.io',
			"icon-cache/{$this->id}.png"
		);
		
		return $this->cache->w->toxml();
	}

}

// ****************

/*
$query = "ang";
$repo = new Repo();
$repo->search($query);
echo $repo->xml();
*/

?>