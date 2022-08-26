<?php
namespace WillFarrell\AlfredPkgMan;


class Grunt extends Repo
{
    protected $id         = 'grunt';
    protected $kind       = 'gruntplugin';
    protected $has_db     = true;
    protected $url        = 'https://npms.io';
    protected $search_url = 'https://api.npms.io/v2/search?q=keywords:gruntplugin+';

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
            $uid = "{$this->id}-{$name}-{$p->version}";

            $this->cache->w->result(
                $uid,
                $this->makeArg($name, $p->links->npm, "{$p->name}: {$p->version}"),
                $name,
                $p->description,
                "icon-cache/{$this->id}.png"
            );

            // only search till max return reached
            if (count($this->cache->w->results()) === $this->max_return) {
                break;
            }
        }

        $this->noResults($query, "{$this->url}/search?q=keywords:gruntplugin+{$query}");

        return $this->asJson();
    }
}

// Test code, uncomment to debug this script from the command-line
// $repo = new Grunt();
// echo $repo->search('contrib');
