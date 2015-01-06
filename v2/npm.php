<?php

/*
npm

*/

// ****************

require_once('cache.php');

class Repo {
	
	private $id = 'npm';
	private $kind = 'packages'; // for none found msg
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
		
		$this->pkgs = $this->cache->get_query_regex($this->id, $query, 'https://www.npmjs.com/search?q='.$query, '/<div class="package-details">([\s\S]*?)<\/div>/i');
		
		foreach($this->pkgs as $pkg) {
			
			// make params
			preg_match('/<h3>([\s\S]*?)<\/h3>/i', $pkg, $matches);
			$title = trim(strip_tags($matches[1]));
		
			preg_match('/<p class="description">([\s\S]*?)<\/p>/i', $pkg, $matches);
		
			$description = html_entity_decode(trim(strip_tags($matches[1])));
			
			preg_match('/<a class="version" href="[\s\S]*?">([\s\S]*?)<\/a>/i', $pkg, $matches);
			//$author = trim(strip_tags($matches[3][0]));
			$version = trim(strip_tags($matches[1]));
	
			$this->cache->w->result(
				$title,
				$this->makeArg($title, 'https://www.npmjs.com/package/'.$title, "*"),
				$title.' ~ v'.$version,//.' by '.$author,
				$description,
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
				"https://www.npmjs.com/search?q={$query}",
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		$this->cache->w->result(
			"{$this->id}-www",
			'https://www.npmjs.com/',
			'Go to the website',
			'https://www.npmjs.com',
			"icon-cache/{$this->id}.png"
		);
		
		return $this->cache->w->toxml();
	}

}

// ****************

/*
$query = "gr";
$repo = new Repo();
$repo->search($query);
echo $repo->xml();
*/

?>