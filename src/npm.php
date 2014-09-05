<?php

/*
npm

*/

// ****************

require_once('cache.php');
require_once('workflows.php');

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
		$this->w = new Workflows();
		
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
			$this->w->result(
				"{$this->id}-min",
				$query,
				"Minimum query length of {$this->min_query_length} not met.",
				"",
				"icon-cache/{$this->id}.png"
			);
			return;
		}
		
		$this->pkgs = $this->cache->get_query_regex($this->id, $query, 'https://www.npmjs.org/search?q='.$query, '/<li class="search-result package">([\s\S]*?)<\/div>/i');
		
		foreach($this->pkgs as $pkg) {
			
			// make params
			preg_match('/<h2>(.*?)<\/h2>/i', $pkg, $matches);
			$title = strip_tags($matches[1]);
		
			preg_match_all('/<p class="description">([\s\S]*?)<\/p>([\s\S]*?)by([\s\S]*?)<\/span>/i', $pkg, $matches);
		
			$author = trim(strip_tags($matches[3][0]));
			$version = trim(strip_tags($matches[2][0]));
			$description = html_entity_decode(trim(strip_tags($matches[1][0])));
	
			$this->w->result(
				$title,
				$this->makeArg($title, 'https://www.npmjs.org/package/'.$title, "*"),
				$title.' ~ v'.$version.' by '.$author,
				$description,
				"icon-cache/{$this->id}.png"
			);
			
			// only search till max return reached
			if ( count ( $this->w->results() ) == $this->max_return ) {
				break;
			}
		}
		
		if ( count( $this->w->results() ) == 0) {
			$this->w->result(
				"{$this->id}-search",
				"https://www.npmjs.org/search?q={$query}",
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		$this->w->result(
			"{$this->id}-www",
			'https://www.npmjs.org/',
			'Go to the website',
			'https://www.npmjs.org',
			"icon-cache/{$this->id}.png"
		);
		
		return $this->w->toxml();
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