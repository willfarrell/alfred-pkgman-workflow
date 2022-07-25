<?php

namespace WillFarrell\AlfredPkgMan;

use Symfony\Component\DomCrawler\Crawler;

class Snap extends Repo
{
    protected $id = 'snap';

    protected $url = 'https://snapcraft.io';

    protected $search_url = 'https://snapcraft.io/search?q=';

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->asJson();
        }

        $html = $this->cache->get_query_raw(
            $this->id,
            $query,
            sprintf('%s%s', $this->search_url, urlencode($query)),
        );

        $crawler = new Crawler($html);
        foreach ($crawler->filter('a.p-media-object--snap') as $node) {
            $node = new Crawler($node);

            $href = str_replace('/', '', $node->attr('href'));
            $title = explode('–', htmlspecialchars_decode($node->attr('title')));
            $name = trim(array_shift($title));
            $desc = trim($title[0] ?? '');

            $this->pkgs[] = compact('name', 'desc', 'href');

            // only search till max return reached
            if (count($this->pkgs) === $this->max_return) {
                break;
            }
        }

        foreach ($this->pkgs as $item) {
            $name = $item['name'];
            $this->cache->w->result(
                $name,
                $this->makeArg($name, "{$this->url}/{$item['href']}"),
                $name,
                $item['desc'],
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

//Test code, uncomment to debug this script from the command-line
//$repo = new AptGet();
//echo $repo->search('leaflet');
