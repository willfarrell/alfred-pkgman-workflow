<?php
namespace WillFarrell\AlfredPkgMan;

require_once('Cache.php');
require_once('Repo.php');

class Puppet extends Repo
{
	protected $id         = 'puppet';
	protected $url        = 'https://forge.puppetlabs.com';
	protected $search_url = 'https://forge.puppetlabs.com/modules?utf-8=✓&sort=rank&q=';
	protected $kind       = 'modules';

	public function search($query) {
		if (!$this->hasMinQueryLength($query)) {
			return $this->xml(); 
		}
		
		$this->pkgs = $this->cache->get_query_regex(
			$this->id,
			$query,
			"{$this->search_url}{$query}",
			'/<li class="clearfix ">([\s\S]*?)<\/li>/i'
		);
		
		foreach($this->pkgs as $pkg) {
			// make params
			preg_match('/<h3>([\s\S]*?)<\/h3>/i', $pkg, $matches);
			$name = trim(strip_tags($matches[1]));
			
			preg_match('/<p>([\s\S]*?)<\/p>/i', $pkg, $matches);
			$description = trim(strip_tags($matches[1]));
			
			preg_match('/Version([\s\S]*?)<\/a>/i', $pkg, $matches);
			$version = trim(strip_tags($matches[1]));
	
			$this->cache->w->result(
				$name,
				$this->makeArg($name, "{$this->url}/{$name}"),
				"{$name} ~ v{$version}",
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
// $repo = new Puppet();
// echo $repo->search('mysql');
