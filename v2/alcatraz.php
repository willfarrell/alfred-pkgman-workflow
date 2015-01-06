<?php

/*
Alcatraz

*/

// ****************

require_once('cache.php');

class Repo {
	
	private $id = 'alcatraz';
	private $kind = 'packages'; // for none found msg
	private $min_query_length = 1; // increase for slow DBs
	private $max_return = 25;
	
	private $cache;
	private $w;
	private $pkgs;
	
	function __construct() {
		
		$this->cache = new Cache();
		
		// get DB here if not dynamic search
		$data = (array) $this->cache->get_db($this->id)->packages;
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
		if ( strlen($query) < $this->min_query_length ) {
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
		
		foreach($this->pkgs as $pkg ) {
			// plugins, color_scheme, project_templates, file_templates
			for( $i = 0; $i < count($pkg); $i++ ) {
				
				if ($this->check($pkg[$i], $query)) {
					$this->cache->w->result(
						$pkg[$i]->url,
						$this->makeArg($pkg[$i]->name, $pkg[$i]->url, "*"),
						$pkg[$i]->name,
						$pkg[$i]->description,
						"icon-cache/{$this->id}.png"
					);
				}
				
				// only search till max return reached
				if ( count ( $this->cache->w->results() ) == $this->max_return ) {
					break;
				}
			}
		}
		
		if ( count( $this->cache->w->results() ) == 0) {
			$this->cache->w->result(
				"{$this->id}-search",
				"http://alcatraz.io//{$query}", // UPDATE NEEDED
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		
		
		
		$this->cache->w->result(
			$this->id.'-www',
			'http://alcatraz.io//',
			'Go to the website',
			'http://alcatraz.io/',
			"icon-cache/".$this->id.".png"
		);
		
		return $this->cache->w->toxml();
	}

}

// ****************

/*
$query = "a";
$repo = new Repo();
$repo->search($query);
echo $repo->xml();
*/

?>