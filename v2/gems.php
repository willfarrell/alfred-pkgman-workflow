<?php

/*
Ruby Gems

*/

// ****************

require_once('cache.php');
require_once('workflows.php');

class Repo {
	
	private $id = 'gems';
	private $kind = 'gems'; // for none found msg
	private $min_query_length = 1; // increase for slow DBs
	private $max_return = 25;
	
	private $cache;
	private $w;
	private $pkgs;
	
	function __construct() {
		
		$this->cache = new Cache();
		$this->w = new Workflows();
		
		// get DB here if not dynamic search
		//$data = (array) $this->cache->get_db($this->id);
		//$this->pkgs = $data;
	}
	
	// return id | url | pkgstr
	function makeArg($id, $url, $version) {
		return $id . "|" . $url . "|" . $id;//"\"$id\":\"$version\",";
	}
	
	function check($pkg, $query) {
		if (   !$query
			|| strpos($pkg->name, $query) !== false
			|| strpos($pkg->info, $query) !== false
		) {
			return true;
		} 
	
		return false;
	}
	
	function search($query) {
		if ( strlen($query) < $this->min_query_length) {
			$this->w->result(
				"{$this->id}-min",
				$query,
				"Minimum query length of {$this->min_query_length} not met.",
				"",
				"icon-cache/{$this->id}.png"
			);
			return;
		}
		
		$this->pkgs = $this->cache->get_query_json(
			$this->id, 
			$query, 
			"https://rubygems.org/api/v1/search?query={$query}"
		);
		
		foreach($this->pkgs as $pkg) {
			if ($this->check($pkg, $query)) {
				$title = $pkg->name;
				
				$this->w->result(
					$title,
					$this->makeArg($title, $pkg->project_uri, '*'),
					$title,
					$pkg->info,
					"icon-cache/{$this->id}.png"
				);
			}
			
			// only search till max return reached
			if ( count ( $this->w->results() ) == $this->max_return ) {
				break;
			}
		}
		
		if ( count( $this->w->results() ) == 0) {
			$this->w->result(
				"{$this->id}-search",
				"https://rubygems.org/search?utf8=%E2%9C%93&query={$query}",
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		$this->w->result(
			"{$this->id}-www",
			'https://rubygems.org/',
			'Go to the website',
			'https://rubygems.org',
			"icon-cache/{$this->id}.png"
		);
		
		return $this->w->toxml();
	}

}

// ****************

/*
$query = "leaflet";
$repo = new Repo();
$repo->search($query);
echo $repo->xml();
*/

?>