<?php
namespace WillFarrell\AlfredPkgMan;


class Hex extends Repo
{
    protected $id         = 'hex';
    protected $kind       = 'components';
    protected $url        = 'https://hex.pm';
    protected $search_url = 'https://hex.pm/api/packages?sort=downloads&search=';

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->asJson();
        }

        $this->pkgs = $this->cache->get_query_json(
            $this->id,
            $query,
            "{$this->search_url}{$query}"
        );

        foreach ($this->pkgs as $pkg) {
            $url = str_replace('api/', '', $pkg->url);
            $this->cache->w->result(
                $pkg->url,
                $this->makeArg($pkg->name, $url),
                $pkg->name,
                $url,
                "icon-cache/{$this->id}.png"
            );

            // only search till max return reached
            if (count($this->cache->w->results()) === $this->max_return) {
                break;
            }
        }

        $this->noResults($query, "{$this->search_url}{$query}");

        return $this->asJson();
    }
}

// Test code, uncomment to debug this script from the command-line
// $repo = new Hex();
// echo $repo->search('test');
