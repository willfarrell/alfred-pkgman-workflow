<?php
namespace WillFarrell\AlfredPkgMan;

require_once('Cache.php');
require_once('Repo.php');

class Composer extends Repo
{
    protected $id         = 'composer';
    protected $kind       = 'packages';
    protected $url        = 'https://packagist.org';

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->xml();
        }

        $this->pkgs = $this->cache->get_query_json(
            $this->id,
            $query,
            "{$this->url}/search.json?q={$query}"
        )->results;

        // foreach ($this->pkgs as $pkg) {
        //     // make params
        //     preg_match('/<a(.*?)<\/a>/i', $pkg, $matches);
        //     $title = strip_tags($matches[0]);

        //     preg_match('/<p class="package-description">([\s\S]*?)<\/p>/i', $pkg, $matches);
        //     $details = strip_tags($matches[1]);

        //     $this->cache->w->result(
        //         $title,
        //         $this->makeArg($title, "{$this->url}/packages/{$title}"),
        //         $title,
        //         $details,
        //         "icon-cache/{$this->id}.png"
        //     );

        foreach ($this->pkgs as $pkg) {
            $title = $pkg->name;

            $this->cache->w->result(
                $pkg->name,
                $this->makeArg(
                    $pkg->name,
                    $pkg->url
                ),
                $title,
                $pkg->description,
                "icon-cache/{$this->id}.png"
            );

            // only search till max return reached
            if (count($this->cache->w->results()) == $this->max_return) {
                break;
            }
        }

        $this->noResults($query, "{$this->url}/?q={$query}");

        return $this->xml();
    }
}

// Test code, uncomment to debug this script from the command-line
// $repo = new Composer();
// echo $repo->search('c');
