<?php

/*
Python pypi

*/

// ****************

require_once('cache.php');

class Repo {
	
	private $id = 'pypi';
	private $kind = 'packages'; // for none found msg
	private $min_query_length = 3; // increase for slow DBs
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
		
		$this->pkgs = $this->cache->get_query_regex($this->id, $query, 'https://pypi.python.org/pypi?%3Aaction=search&term='.$query.'&submit=search', '/<tr class="(.*?)">([\s\S]*?)<\/tr>/i', 2);
		
		foreach($this->pkgs as $pkg) {
			
			// make params
			// name
			preg_match('/<a href="(.*?)">(.*?)<\/a>/i', $pkg, $matches);
			$title = str_replace("&nbsp;", " ", strip_tags($matches[0]));
			$url = strip_tags($matches[1]);
			
			preg_match_all('/<td>([\s\S]*?)<\/td>/i', $pkg, $matches);
			$downloads = strip_tags($matches[1][1]);
			$details = strip_tags($matches[1][2]);
		
			$this->cache->w->result(
				$title,
				$this->makeArg($title, 'https://pypi.python.org'.$url, "*"),
				$title."    ".$downloads,
				$details,
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
				"https://pypi.python.org/pypi?%3Aaction=search&term={$query}&submit=search",
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		$this->cache->w->result(
			"{$this->id}-www",
			'https://pypi.python.org/',
			'Go to the website',
			'https://pypi.python.org',
			"icon-cache/{$this->id}.png"
		);
		
		return $this->cache->w->toxml();
	}

}

// ****************

/*
$query = "lib";
$repo = new Repo();
$repo->search($query);
echo $repo->xml();
*/

?>