<?php

/*
Java gradle

*/

// ****************

require_once('cache.php');

class Repo {
	
	private $id = 'gradle';
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
		
		$this->pkgs = $this->cache->get_query_json($this->id, $query, 'http://search.maven.org/solrsearch/select?q='.$query.'&rows=10&wt=json')->response->docs;
		
		foreach($this->pkgs as $pkg) {
			
			// make params
			$title = $pkg->a.' ('.$pkg->latestVersion.')';
			$url = 'http://search.maven.org/#artifactdetails%7C'.$pkg->g.'%7C'.$pkg->a.'%7C'.$pkg->latestVersion.'%7C'.$pkg->p;
			$details = 'GroupId: '.$pkg->id;
	
			$this->cache->w->result(
				$pkg->a,
				$this->makeArg($pkg->a, $url, "*"),
				$title,
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
				"http://mvnrepository.com/search.html?query={$query}",
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		$this->cache->w->result(
			"{$this->id}-www",
			'http://www.gradle.org/',
			'Go to the website',
			'http://www.gradle.org',
			"icon-cache/{$this->id}.png"
		);
		
		return $this->cache->w->toxml();
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