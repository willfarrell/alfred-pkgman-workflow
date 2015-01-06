<?php

/*
grunt

*/

// ****************

require_once('cache.php');

class Repo {
	
	private $id = 'grunt';
	private $kind = 'plugins'; // for none found msg
	private $min_query_length = 1; // increase for slow DBs
	private $max_return = 25;
	
	private $cache;
	private $w;
	private $pkgs;
	
	function __construct() {
		
		$this->cache = new Cache();
		
		// get DB here if not dynamic search
		$data = (array) $this->cache->get_db($this->id)->aaData;
		$this->pkgs = $data;
	}
	
	// return id | url | pkgstr
	function makeArg($id, $url, $version) {
		return $id . "|" . $url . "|" . $id;//"\"$id\":\"$version\",";
	}
	
	function check($pkg, $query) {
		if (   !$query
			|| strpos($pkg->name, $query) !== false
			|| strpos($pkg->ds, $query) !== false
		) {
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
				$title = str_replace('grunt-', '', $pkg->name); // remove grunt- from title
			
				// add author to title
				if (isset($pkg->author)) {
					$title .= " by " . $pkg->author;
				}
				$url = 'https://www.npmjs.org/package/' . $pkg->name;
				
				//if (strpos($plugin->description, "DEPRECATED") !== false) { continue; } // skip DEPRECATED repos
				$this->cache->w->result(
					$pkg->name,
					$this->makeArg($pkg->name, $url, "*"),
					$title,
					$pkg->ds,
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
				"http://gruntjs.com/plugins/{$query}",
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		$this->cache->w->result(
			"{$this->id}-www",
			'http://gruntjs.com/',
			'Go to the website',
			'http://gruntjs.com',
			"icon-cache/{$this->id}.png"
		);
		
		return $this->cache->w->toxml();
	}

}

// ****************

/*
$query = 'contrib';
$repo = new Repo();
$repo->search($query);
echo $repo->xml();
*/

?>