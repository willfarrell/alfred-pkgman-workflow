<?php
namespace WillFarrell\AlfredPkgMan;


class NuGet extends Repo
{
    protected $id               = 'nuget';
    protected $url              = 'https://www.nuget.org';
    protected $search_url       = 'https://www.nuget.org/packages?q=';
    protected $package_url      = 'https://www.nuget.org/packages/';

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->asJson();
        }

        $this->pkgs = $this->cache->get_query_json(
            $this->id,
            $query,
            "https://api-v2v3search-1.nuget.org/query?q={$query}"
        );

        foreach ($this->pkgs->data as $pkg) {
            $title = $pkg->title;
            $version = $pkg->version;
            $url = "{$package_url}$pkg->id";
            $this->cache->w->result(
                $title,
                $this->makeArg($title, $url, "nuget {$pkg->id} ~> {$pkg->version}"),
                $title,
                $pkg->description,
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
// $repo = new NuGet();
// echo $repo->search('nhibernate');
