<?php

namespace WillFarrell\AlfredPkgMan;

class Brew extends Repo
{
    protected $id = 'brew';

    protected $kind = 'plugins';

    protected $url = 'https://brew.sh/#search-bar';

    protected $baseUrl = 'https://formulae.brew.sh/api';

    protected $search_url = ['formula', 'cask'];

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->asJson();
        }

        foreach ($this->search_url as $part) {
            $data = $this->cache->get_query_json(
                "{$this->id}-$part",
                $query,
                "{$this->baseUrl}/{$part}.json"
            );

            if ($match = $this->findMatch($data, $query)) {
                array_map(function ($m) {
                    $this->pkgs[] = $m;
                }, $match);
            }
        }

        foreach ($this->pkgs as $pkg) {
            $title = is_array($pkg->name) ? $pkg->name[0] : $pkg->name;
            $id = is_array($pkg->name) ? $pkg->token : $pkg->name;
            $icon = is_array($pkg->name) ? $this->id . '-cask' : $this->id;

            $this->cache->w->result(
                $id,
                $this->makeArg($id, $pkg->homepage),
                $title,
                $pkg->desc,
                "icon-cache/{$icon}.png"
            );

            // only search till max return reached
            if (count($this->cache->w->results()) === $this->max_return) {
                break;
            }
        }

        $this->noResults($query, "{$this->url}{$query}");

        return $this->asJson();
    }

    private function findMatch(array $pkgs, $query)
    {
        return array_filter($pkgs, static function ($pkg) use ($query) {
            $pkgName = is_array($pkg->name) ? $pkg->name[0] : $pkg->name;
            $match = levenshtein($query, $pkgName, 4,2,3);

            return stripos($pkgName, $query) !== false || $match < 4;
        });
    }
}

// Test code, uncomment to debug this script from the command-line
// $repo = new Brew();
// echo $repo->search('pk');
