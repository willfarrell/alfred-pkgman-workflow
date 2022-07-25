<?php

namespace WillFarrell\AlfredPkgMan;

use Symfony\Component\DomCrawler\Crawler;

class AptGet extends Repo
{
    protected $id = 'apt-get';

    protected $url = 'https://packages.ubuntu.com';

    protected $search_url = 'https://packages.ubuntu.com/search?searchon=names&suite=jammy&section=all&keywords=';

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
        foreach ($crawler->filter('#psearchres h3') as $node) {
            $node = new Crawler($node);

            $name = str_replace('Package ', '', $node->text());
            $href = $node->nextAll()->first()->filter('a.resultlink')->attr('href');

            preg_match('#</a>(.*)<br>#siU', $node->nextAll()->first()->children('li')->html(), $matches);
            if (count($matches) < 2) {
                preg_match('#</a>:(.*)<a.*>(.*)</a#siU', $node->nextAll()->first()->children('li')->html(), $matches);
                $matches = [
                    $matches[0],
                    $matches[1] . $matches[2],
                ];
            }

            $desc = (count($matches) < 2)
                ? ''
                : preg_replace('/([\n\t]+|[\s]+)/', ' ', trim(strip_tags($matches[1])));

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
