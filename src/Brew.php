<?php
namespace WillFarrell\AlfredPkgMan;

require_once('Cache.php');
require_once('Repo.php');

class Brew extends Repo
{
    protected $id         = 'brew';
    protected $kind       = 'plugins';
    protected $url        = 'http://searchbrew.com';
    protected $search_url = 'http://searchbrew.com/search?q=';

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->xml();
        }

        $this->pkgs = $this->cache->get_query_json(
            $this->id,
            $query,
            "{$this->search_url}{$query}"
        );

        foreach ($this->pkgs->data as $pkg) {
            $title = $pkg->title;

            $this->cache->w->result(
                $title,
                $this->makeArg($title, $pkg->homepage),
                $title,
                $pkg->description,
                "icon-cache/{$this->id}.png"
            );

            // only search till max return reached
            if (count($this->cache->w->results()) == $this->max_return) {
                break;
            }
        }

        $this->noResults($query, "{$this->search_url}{$query}");

        return $this->xml();
    }
}

// Test code, uncomment to debug this script from the command-line
// $repo = new Brew();
// echo $repo->search('pk');
