<?php

namespace WillFarrell\AlfredPkgMan;


class Gems extends Repo
{
    protected $id         = 'gems';
    protected $kind       = 'gems';
    protected $url        = 'https://rubygems.org';
    protected $search_url = 'https://rubygems.org/search?utf8=%E2%9C%93&query=';

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->asJson();
        }

        $this->pkgs = $this->cache->get_query_json(
            $this->id,
            $query,
            "{$this->url}/api/v1/search?query={$query}"
        );

        foreach ($this->pkgs as $pkg) {
            if ($this->check($pkg, $query, 'name', 'info')) {
                $title = $pkg->name;
                $version = $pkg->version;
                $this->cache->w->result(
                    $title,
                    $this->makeArg($title, $pkg->project_uri),
                    $title,
                    "{$version} - $pkg->info",
                    "icon-cache/{$this->id}.png"
                );
            }

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
// $repo = new Gems();
// echo $repo->search('cap');
