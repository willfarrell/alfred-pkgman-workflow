<?php
namespace WillFarrell\AlfredPkgMan;

require_once('Cache.php');
require_once('Repo.php');

class Docker extends Repo
{
	protected $id         = 'docker';
	protected $kind       = 'images';
	protected $url        = 'https://registry.hub.docker.com';
	protected $search_url = 'https://registry.hub.docker.com/search?q=';

	public function search($query)
	{
		if (!$this->hasMinQueryLength($query)) {
			return $this->xml(); 
		}
		
		$this->pkgs = $this->cache->get_query_regex(
			$this->id,
			$query,
			"{$this->search_url}{$query}",
			'/(<a href="\/(_|u)\/.*"><div class="repo-list-item box">[\s\S]*?<\/a>)/i',
			1
		);
		
		foreach($this->pkgs as $pkg) {
			
			// make params
			preg_match('/<a href="(.*?)">[\s\S]*?<h2>([\s\S]*?)<[\s\S]*<\/h2>([\s\S]*?)<\/div>/i', $pkg, $matches);
			$title = trim(preg_replace('/\s+/', '', $matches[2]));
			$url = $this->url . trim(preg_replace('/\s+/', '', $matches[1]));
			$description = trim(preg_replace('/\s+/', ' ', $matches[3]));
			if (!$description || trim(preg_replace('/\s+/', '', $matches[3])) == '') {
				$description = $url;
			}
		
			preg_match('/<span class="timesince" data-time=".*">\s?([\s\S]*?)\s?<\/span>/i', $pkg, $matches);
			$updated = trim(preg_replace('/\s+/', ' ', $matches[1]));
	
			$this->cache->w->result(
				$title,
				$this->makeArg($title, $url),
				$title.' ~ '.$updated,
				$description,
				"icon-cache/{$this->id}.png"
			);
			
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
// $repo = new Docker();
// echo $repo->search('ng');
