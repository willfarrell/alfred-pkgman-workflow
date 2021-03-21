<?php
namespace WillFarrell\AlfredPkgMan;


class Yarn extends Repo
{
    protected $id         = 'yarn';
    protected $url        = 'https://npms.io';
    protected $search_url = 'https://api.npms.io/v2/search?q=';
    protected $yarn_url   = 'https://yarnpkg.com/en/package/';

    public function search($query)
    {
        $query = str_replace(' ', '+', $query);
        if (!$this->hasMinQueryLength($query)) {
            return $this->asJson();
        }

        $this->pkgs = $this->cache->get_query_json(
            $this->id,
            $query,
            "{$this->search_url}{$query}&size={$this->max_return}"
        );

        foreach ($this->pkgs->results as $pkg) {
            $p = $pkg->package;
            $name = $p->name;

            $this->cache->w->result(
                $this->id,
                $this->makeArg($name, $this->yarn_url.$p->name, "{$p->name}: {$p->version}"),
                $name,
                $p->description,
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
// $repo = new Yarn();
// echo $repo->search('gr');
