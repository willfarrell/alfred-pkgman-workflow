<?php
namespace WillFarrell\AlfredPkgMan;

// README: Turn off all error reporting
// Comment this out to enable error reporting for debugging
error_reporting(0);

class Npm extends Repo
{
    protected $id         = 'npm';
    protected $url        = 'https://npms.io';
    protected $search_url = 'https://api.npms.io/v2/search?q=';

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
            $p    = $pkg->package;
            $name = $p->name;
            $uid  = "{$this->id}-{$name}-{$p->version}";

            // README: Description is not required to publish a module, so we check for it
            //          and fall back to an empty string if it is missing
            $p->description
                ? $description = $p->description
                : $description = '';

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

        $this->noResults($query, "{$this->search_url}{$query}");

        return $this->asJson();
    }
}

// Test code, uncomment to debug this script from the command-line
// $repo = new Npm();
// echo $repo->search('gr');
