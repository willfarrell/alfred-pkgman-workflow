<?php
namespace WillFarrell\AlfredPkgMan;


class Maven extends Repo
{
    protected $id         = 'maven';
    protected $kind       = 'libraries';
    protected $url        = 'https://search.maven.org';
    protected $search_url = 'https://search.maven.org/#search%7Cga%7C1%7C';

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->asJson();
        }

        $this->pkgs = $this->cache->get_query_json(
            $this->id,
            $query,
            "{$this->url}/solrsearch/select?q={$query}&rows=10&wt=json"
        )->response->docs;

        foreach ($this->pkgs as $pkg) {
            // make params
            $title = "{$pkg->a} (v{$pkg->latestVersion})";
            $url = "{$this->url}/artifact/{$pkg->g}/{$pkg->a}/{$pkg->latestVersion}/{$pkg->p}";
            $details = "GroupId: {$pkg->id}";

            $this->cache->w->result(
                $pkg->a,
                $this->makeArg($pkg->a, $url),
                $title,
                $details,
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
// $repo = new Maven();
// echo $repo->search('leaflet');
