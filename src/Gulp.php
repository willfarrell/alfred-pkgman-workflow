<?php
namespace WillFarrell\AlfredPkgMan;

require_once('Cache.php');
require_once('Repo.php');

class Gulp extends Repo
{
	protected $id         = 'gulp';
	protected $kind       = 'plugins';
	protected $url        = 'http://gulpjs.com';
	protected $search_url = 'http://gulpjs.com/plugins/#?q=';
	protected $has_db     = true;

	public function search($query)
	{
		if (!$this->hasMinQueryLength($query)) {
			return $this->xml(); 
		}
		
		foreach($this->pkgs->results as $pkg) {
			
			// make params
			if ($this->check($pkg, $query)) {
				$title = str_replace('gulp-', '', $pkg->name); // remove gulp- from title
			
				// add version to title
				if (isset($pkg->version)) {
					$title .= " v{$pkg->version}";
				}
				// add author to title
				if (isset($pkg->author)) {
					$title .= " by {$pkg->author}";
				}
				
				// skip DEPRECATED repos
				// if (strpos($plugin->description, "DEPRECATED") !== false) {
				// 	continue;
				// }

				$this->cache->w->result(
					$pkg->name,
					$this->makeArg($pkg->name, $pkg->homepage),
					$title,
					$pkg->description,
					"icon-cache/{$this->id}.png"
				);
			}

			// only search till max return reached
			if ( count ( $this->cache->w->results() ) == $this->max_return ) {
				break;
			}
		}

		$this->noResults($query, "{$this->search_url}{$query}");

		return $this->xml();
	}
}

// Test code, uncomment to debug this script from the command-line
// $repo = new Gulp();
// echo $repo->search('min');
