<?php

/*
Brew

*/

// ****************

require_once('cache.php');

class Repo {
	
	private $id = 'brew';
	private $kind = 'plugins'; // for none found msg
	private $min_query_length = 1; // increase for slow DBs
	private $max_return = 25;
	
	private $cache;
	private $w;
	private $pkgs;
	
	function __construct() {
		
		$this->cache = new Cache();
		
		// get DB here if not dynamic search
		//$data = (array) $this->cache->get_db($this->id);
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
		
		// special case - exact match
		$is_redirect = $this->cache->get_query_data($this->id, $query, 'http://braumeister.org/search/'.$query);
		
		if ($is_redirect === '<html><body>You are being <a href="http://braumeister.org/formula/'.$query.'">redirected</a>.</body></html>') {
			// special case when exact match
			$this->pkgs = $this->cache->get_query_regex($this->id, $query, 'http://braumeister.org/formula/'.$query, '/<div id="content">([\s\S]*?)<div id="deps">/i', 1);
			$pkg = $this->pkgs[0];
			
			// name
			$title = $query;
			
			// version
			preg_match('/<strong class="version spec-stable">([\s\S]*?)<\/strong>/i', $pkg, $matches);
			$version = trim(strip_tags($matches[0]));
			
			// details
			preg_match('/Homepage: <em><a href="(.*?)">(.*?)<\/a>/i', $pkg, $matches);
			$details = strip_tags($matches[1]);
			
			$this->cache->w->result(
				$title,
				"http://braumeister.org/formula/{$title}",
				"{$title} ~ {$version}",
				$details,
				"icon-cache/{$this->id}.png"
			);
			
			return;
		}
		// END special case - exact match
		
		$this->pkgs = $this->cache->get_query_regex($this->id, $query, 'http://braumeister.org/search/'.$query, '/<div class="formula (odd|even)">([\s\S]*?)<\/div>/i', 2);
		
		foreach($this->pkgs as $pkg) {
			// name
			preg_match('/<a class="formula" href="(.*?)">(.*?)<\/a>/i', $pkg, $matches);
			$title = strip_tags($matches[0]);
			
			// version
			preg_match('/<strong class="version spec-stable">([\s\S]*?)<\/strong>/i', $pkg, $matches);
			$version = trim(strip_tags($matches[0]));
			
			// details
			preg_match('/Homepage: <a href="(.*?)">(.*?)<\/a>/i', $pkg, $matches);
			$details = strip_tags($matches[1]);
			
			$this->cache->w->result(
				$title,
				"http://braumeister.org/formula/{$title}",
				"{$title} ~ {$version}",
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
				$this->id,
				"http://braumeister.org/search/{$query}",
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		
		$this->cache->w->result(
			"{$this->id}-www",
			'http://braumeister.org/',
			'Go to the website',
			'http://braumeister.org',
			"icon-cache/{$this->id}.png"
		);
		
		return $this->cache->w->toxml();
	}

}

// ****************

/*
$query = "n";
$repo = new Repo();
$repo->search($query);
echo $repo->xml();
*/

?>