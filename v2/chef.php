<?php

/*
Chef

*/

// ****************

require_once('cache.php');

class Repo {
	
	private $id = 'chef';
	private $kind = 'cookbooks'; // for none found msg
	private $min_query_length = 1; // increase for slow DBs
	private $max_return = 25;
	
	private $cache;
	private $w;
	private $pkgs;
	
	function __construct() {
		
		$this->cache = new Cache();
		
		// get DB here if not dynamic search
		//$data = (array) $this->cache->get_db($this->id);
		//$this->pkgs = $data;
	}
	
	// return id | url | pkgstr
	function makeArg($id, $url, $version) {
		return $id . "|" . $url . "|" . $id;//"\"$id\":\"$version\",";
	}
	
	function check($pkg, $query) {
		if (!$query) { return true; }

		if (   strpos($pkg->cookbook_name, $query) !== false
			|| strpos($pkg->cookbook_description, $query) !== false
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
		
		$this->pkgs = $this->cache->get_query_json($this->id, $query, "https://supermarket.getchef.com/api/v1/search?q={$query}");
		
		foreach ($this->pkgs->items as $pkg) {
			if ($this->check($pkg, $query)) {
				$title = $pkg->cookbook_name;
		
				// add author to title
				if (isset($pkg->cookbook_maintainer)) {
					$title .= " by {$pkg->cookbook_maintainer}";
				}
		
				$this->cache->w->result(
					$pkg->cookbook_name,
					$this->makeArg($pkg->cookbook_name, "https://supermarket.getchef.com/cookbooks/{$pkg->cookbook_name}", "*"),
					$title,
					$pkg->cookbook_description,
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
				"http://supermarket.getchef.com/cookbooks/{$query}",
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		
		$this->cache->w->result(
			"{$this->id}-www",
			"http://supermarket.getchef.com/",
			"Go to the website",
			"http://supermarket.getchef.com",
			"icon-cache/{$this->id}.png"
		);
		
		return $this->cache->w->toxml();
	}

}

// ****************

/*
$query = "or";
$repo = new Repo();
$repo->search($query);
echo $repo->xml();
*/

?>