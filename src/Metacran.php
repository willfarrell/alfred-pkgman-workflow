<?php
namespace WillFarrell\AlfredPkgMan;

require_once('Cache.php');
require_once('Repo.php');

class Metacran extends Repo
{
    protected $id         = 'metacran';
    protected $kind       = 'package';
    protected $url        = 'https://www.r-pkg.org';
    protected $search_url = 'http://seer.r-pkg.org:9200/_search?q=';

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->xml();
        }

        $this->pkgs = $this->cache->get_query_json(
            $this->id,
            $query,
            "{$this->search_url}{$query}&size={$this->max_return}"
        )->hits->hits;

        foreach ($this->pkgs as $pkg) {
            // make params
            $title   = "{$pkg->_source->Package} (v{$pkg->_source->Version}) - Score: {$pkg->_score}";
            $url     = "{$this->url}/pkg/{$pkg->_source->Package}";
            $details = "{$pkg->_source->Description}";

            $this->cache->w->result(
                $pkg->_source->Package,
                $this->makeArg($pkg->_source->Package, $url),
                $title,
                $details,
                "icon-cache/{$this->id}.png"
            );

            // only search till max return reached
            if (count($this->cache->w->results()) == $this->max_return) {
                break;
            }
        }

        $this->noResults($query, "{$this->url}/search.html?q={$query}");

        return $this->xml();
    }
}

// Test code, uncomment to debug this script from the command-line
// $repo = new Metacran();
// echo $repo->search('ggplot2');
