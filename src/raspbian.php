<?php

/*
Raspbian

*/

// ****************

require_once('cache.php');
require_once('workflows.php');

class Repo {
	
	private $id = 'raspbian';
	//private $min_query_length = 3; // use when loading in large DBs
	private $max_return = 25;
	private $cache;
	private $w;
	private $pkgs;
	
	function __construct() {
		ini_set('memory_limit', '-1');
		
		$this->cache = new Cache();
		$this->w = new Workflows();
		
		// get DB here if not dynamic search
		$data = (array) $this->cache->get_db('raspbian');
		//print_r($data[0]);
		$pkgs = explode("\n\n", $data[0]);
		array_pop($pkgs); // remove file end
		
		$this->pkgs = [];
		for ($i = 0, $l = count($pkgs); $i < $l; $i++) {
			$pkg = $pkgs[$i];
			//preg_match("/Package: ([^\n]+)\n[\s\S]*?Version: ([^\n]+)\n[\s\S]*?Installed-Size: ([^\n]+)\n[\s\S]*?Maintainer: ([^\n]+?) <([^\n]+?)>\n[\s\S]*Size: ([^\n]+)\n[\s\S]*?Description: (.*?)\n[\s\S]*?Homepage: ([^\n]+)\n[\s\S]*?Filename: ([^\n]+)/i", $pkgs[$i], $matches);
			
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
			
			//break;
		}
		//print_r($this->pkgs);
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
				
				//if (strpos($plugin->description, "DEPRECATED") !== false) { continue; } // skip DEPRECATED repos
				$this->w->result( $pkg["name"], "https://packages.debian.org/wheezy/".$pkg["name"], $title, $pkg["description"], "icon-cache/".$this->id.".png" );
			}
			
			// only search till max return reached
			if ( count ( $this->w->results() ) == $this->max_return ) {
				break;
			}
		}
		
	}
	
	function xml() {
		if ( count( $this->w->results() ) == 0) {
			/*if($query) {
				$this->w->result( $this->id, 'http://sindresorhus.com/bower-components/#!/search/'.$query, 'No components were found that matched "'.$query.'"', 'Click to see the results for yourself', 'icon-cache/bower.png' );
			}*/
			$this->w->result( $this->id."-www", 'http://www.raspbian.org/', 'Go to the website', 'http://www.raspbian.org', "icon-cache/".$this->id.".png" );
		}
		
		return $this->w->toxml();
	}

}

// ****************

$query = "lib";
$repo = new Repo();
$repo->search($query);
echo $repo->xml();

?>