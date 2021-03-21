<?php
namespace WillFarrell\AlfredPkgMan;


class Pypi extends Repo
{
    protected $id               = 'pypi';
    protected $url              = 'https://pypi.org';
    protected $search_url       = 'https://pypi.org/search/?q=';
    protected $min_query_length = 3;

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->asJson();
        }

        $this->pkgs = $this->cache->get_query_regex(
            $this->id,
            $query,
            "{$this->search_url}{$query}",
            '/<a class="package-snippet" ([\s\S]*?)<\/a>/i',
            1
        );

        foreach ($this->pkgs as $pkg) {
            // make params
            // name
            preg_match('/<span class="package-snippet__name">(.*?)<\/span>/i', $pkg, $matches);
            $title = str_replace("&nbsp;", " ", $matches[1]);

            preg_match('/href="(.*?)">/i', $pkg, $matches);
            $url = $matches[1];

            preg_match('/<span class="package-snippet__version">(.*?)<\/span>/i', $pkg, $matches);
            $downloads = $matches[1];

            preg_match('/<p class="package-snippet__description">(.*?)<\/p>/i', $pkg, $matches);
            $details = $matches[1];

            $this->cache->w->result(
                $title,
                $this->makeArg($title, "{$this->url}{$url}"),
                "{$title}    {$downloads}",
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

// $repo = new Pypi();
// echo $repo->search('lib');
