<?php
namespace WillFarrell\AlfredPkgMan;

require_once('Cache.php');
require_once('Repo.php');

class Npm extends Repo
{
	protected $id         = 'npm';
	protected $url        = 'https://www.npmjs.com';
	protected $search_url = 'https://www.npmjs.com/search?q=';

	public function search($query)
	{
		if (!$this->hasMinQueryLength($query)) {
			return $this->xml(); 
		}
		
		$this->pkgs = $this->cache->get_query_regex(
			$this->id,
			$query,
			"{$this->search_url}{$query}",
			'/<div class="package-details">([\s\S]*?)<\/div>/i'
		);
		
		foreach($this->pkgs as $pkg) {
			
			// make params
			preg_match('/<a class="name" href="[\s\S]*?">([\s\S]*?)<\/a>/i', $pkg, $matches);
			$title = trim(strip_tags($matches[1]));

            //preg_match('/<a class="author" href="[\s\S]*?">([\s\S]*?)<\/a>/i', $pkg, $matches);
			//$author = trim(strip_tags($matches[1]));
		
			preg_match('/<p class="description">([\s\S]*?)<\/p>/i', $pkg, $matches);
		
			$description = html_entity_decode(trim(strip_tags($matches[1])));
			
			//preg_match('/<span class="stars"><i class="icon-star"></i>([\s\S]*?)<\/span>/i', $pkg, $matches);
            //$stars = trim(strip_tags($matches[1]));

			preg_match('/<span class="version">([\s\S]*?)<\/span>/i', $pkg, $matches);
			$version = trim(strip_tags($matches[1]));
	
			$this->cache->w->result(
				$title,
				$this->makeArg($title, "{$this->url}/package/{$title}"),
				"{$title} ~ {$version}", //.' by '.$author,
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
// $repo = new Npm();
// echo $repo->search('gr');
