<?php

namespace WillFarrell\AlfredPkgMan;

class Snap extends Repo
{
    protected $id = 'snap';

    protected $url = 'https://snapcraft.io';

    protected $search_url = 'https://api.snapcraft.io/api/v1/snaps/search?scope=wide&arch=wide&exclude_non_free=false&confinement=strict,classic&q=';

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->asJson();
        }

        $data = $this->cache->get_query_json(
            $this->id,
            $query,
            sprintf('%s%s', $this->search_url, urlencode($query))
        );

        if (count($data->_embedded->{'clickindex:package'}) === 0) {
            $this->noResults($query, "{$this->search_url}{$query}");

            return $this->asJson();
        }

        foreach ($data->_embedded->{'clickindex:package'} as $snap) {
            $href = $snap->package_name;
            $name = $snap->package_name;
            $desc = $snap->summary;

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
