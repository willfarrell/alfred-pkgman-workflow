<?php

/*
Raspbian

*/

// ****************

require_once('cache.php');

class Repo {
	
	private $id = 'raspbian';
	private $kind = 'packages'; // for none found msg
	private $min_query_length = 1; // increase for slow DBs
	private $max_return = 25;
	
	private $cache;
	private $w;
	private $pkgs;
	
	function __construct() {
		
		$this->cache = new Cache();
		
		// get DB here if not dynamic search
		$data = (array) $this->cache->get_db($this->id);
		$pkgs = explode("\n\n", $data[0]);
		array_pop($pkgs); // remove file end
		
		$this->pkgs = [];
		for ($i = 0, $l = count($pkgs); $i < $l; $i++) {
			$pkg = $pkgs[$i];
			
			preg_match("/Package: ([^\n]+)/i", $pkg, $name);
			preg_match("/Version: ([^\n]+)/i", $pkg, $version);
			preg_match("/Installed-Size: ([^\n]+)/i", $pkg, $installed_size);
			preg_match("/Maintainer: ([^\n]+?) <([^\n]+?)>/i", $pkg, $maintainer);
			preg_match("/Size: ([^\n]+)/i", $pkg, $size);
			preg_match("/Description: (.*?)\n/i", $pkg, $description);
			//preg_match("/Homepage: ([^\n]+)/i", $pkg, $homepage);
			preg_match("/Filename: ([^\n]+)/i", $pkg, $filename);
			
			//print_r($description);
			//if (!count($homepage)) {
				//echo $pkg;
				//$homepage[1] = '';
			//}
			
			$this->pkgs[] = [
				"name" => $name[1],
				"version" => $version[1],
				"installed-size" => $installed_size[1],
				"maintainer" => $maintainer[1]." <".$maintainer[2].">",
				"author" => $maintainer[1],
				"email" => $maintainer[2],
				"size" => $size[1],
				"description" => $description[1],
				//"homepage" => $homepage[1],
				"filename" => $filename[1]
			];
		}
	}
	
	// return id | url | pkgstr
	function makeArg($id, $url, $version) {
		return $id . "|" . $url . "|" . "$id";
	}
	
	function check($pkg, $query) {
		if (!$query) { return true; }
		if (strpos($pkg["name"], $query) !== false) {
			return true;
		} else if (strpos($pkg["description"], $query) !== false) {
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
		
		foreach($this->pkgs as $pkg) {
			if ($this->check($pkg, $query)) {
				$title = $pkg["name"];
			
				// add version to title
				if (isset($pkg["version"])) {
					$title .= " v".$pkg["version"];
				}
				// add author to title
				if (isset($pkg->author)) {
					$title .= " by " . $pkg["author"];
				}
				$url = "https://packages.debian.org/wheezy/".$pkg["name"];
				//if (strpos($plugin->description, "DEPRECATED") !== false) { continue; } // skip DEPRECATED repos
				$this->cache->w->result(
					$pkg["name"],
					$this->makeArg($pkg["name"], $url, $pkg["version"]),
					$title,
					$pkg["description"],
					"icon-cache/".$this->id.".png"
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
				"https://packages.debian.org/wheezy/{$query}",
				"No {$this->kind} were found that matched \"{$query}\"",
				"Click to see the results for yourself",
				"icon-cache/{$this->id}.png"
			);
			
		}
	}
	
	function xml() {
		
		
		$this->cache->w->result(
			"{$this->id}-www",
			'http://www.raspbian.org/',
			'Go to the website',
			'http://www.raspbian.org',
			"icon-cache/".$this->id.".png"
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