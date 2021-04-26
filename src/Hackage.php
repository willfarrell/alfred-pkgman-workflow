<?php
namespace WillFarrell\AlfredPkgMan;

class Hackage extends Repo
{
    protected $id               = 'hackage';
    protected $url              = 'https://hackage.haskell.org';
    protected $search_url       = 'https://hackage.haskell.org/packages/search.json?terms=';
    protected $package_url      = 'https://hackage.haskell.org/package/';

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
            $name = $pkg->name;
            $url = "{$this->package_url}$pkg->name";
            $this->cache->w->result(
                $name,
                $this->makeArg($name, $url, "- {$name}"),
                $name,
                "",
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
// $repo = new Hackage();
// echo $repo->search('hspec');
