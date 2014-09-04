<?php

/*
Bower

*/

// ****************

require_once('cache.php');
require_once('workflows.php');

class Repo {
	
	private $id = 'alcatraz';
	private $min_query_length = 1; // increase for slow DBs
	private $max_return = 25;
	
	private $cache;
	private $w;
	private $pkgs;
	
	function __construct() {
		
		$this->cache = new Cache();
		$this->w = new Workflows();
		
		// get DB here if not dynamic search
		$data = (array) $this->cache->get_db($this->id);
		$this->$pkgs = $data;
	}
	
	// return id | url | pkgstr
	function makeArg($id, $url, $version) {
		return $id . "|" . $url . "|" . $id;//"\"$id\":\"$version\",";
	}
	
	function check($pkg, $query) {
		if (!$query) { return true; }
		if (strpos($item->name, $query) !== false) {
			return true;
		} else if (strpos($item->description, $query) !== false) {
			return true;
		}
		return false;
	}
	
	function search($query) {
		if ( count($query) < $this->min_query_length) {
			$this->w->result(
				"{$this->id}-min",
				$query,
				"Minimum query length of {$this->min_query_length} not met.",
				"",
				"icon-cache/{$this->id}.png"
			);
			return;
		}
		
		foreach($this->pkgs->packages as $package ) {
			// plugins, color_scheme, project_templates, file_templates
			for( $i = 0; $i < count($package); $i++ ) {
				
				if (check($package[$i], $query)) {
					$this->w->result(
						$package[$i]->url,
						$this->makeArg($package[$i]->name, $package[$i]->url, "*"),
						$package[$i]->name, $package[$i]->description,
						"icon-cache/{$this->id}.png"
					);
				}
				
				// only search till max return reached
				if ( count ( $this->w->results() ) == $this->max_return ) {
					break;
				}
			}
		}
		
		if ( count( $this->w->results() ) == 0) {
			$this->w->result(
				"{$this->id}-search",
				"http://mneorr.github.io/Alcatraz/{$query}",
				"No components were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
		}
	}
	
	function xml() {
		
		
		
		$this->w->result(
			$this->id.'-www',
			'http://mneorr.github.io/Alcatraz/',
			'Go to the website',
			'http://mneorr.github.io/Alcatraz',
			"icon-cache/".$this->id.".png"
		);
		
		return $this->w->toxml();
	}

}

// ****************

/*$query = "leaflet";
$repo = new Repo();
$repo->search($query);
echo $repo->xml();*/

?>