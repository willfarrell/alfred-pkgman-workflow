<?php

namespace WillFarrell\AlfredPkgMan;

class Cache
{
    /**
     * @var Workflows
     */
    public $w;
    private $queries = [];

    public $cache_age = 14;
    public $dbs = [
        "alcatraz" => "https://raw.githubusercontent.com/mneorr/alcatraz-packages/master/packages.json",
        "apple" => "http://cocoadocs.org/apple_documents.jsonp", // CocoaDocs
        "brew" => [
            "formula" => "https://formulae.brew.sh/api/formula.json",
            "cask" => "https://formulae.brew.sh/api/cask.json",
        ],
        "cocoa" => "http://cocoadocs.org/documents.jsonp",
        "grunt" => "http://gruntjs.com/plugin-list.json",
        "raspbian" => "http://archive.raspbian.org/raspbian/dists/wheezy/main/binary-armhf/Packages",
        "yo" => "https://api.npms.io/v2/search?q=keywords:yeoman-generator%20generator-*",
    ];
    public $query_file = "queries";

    public function __construct()
    {
        // Some package managers (like brew or gems) have a very large JSON payload that causes
        // PHP to globally allocate more than would allow in its default configuration (128M)
        ini_set('memory_limit', '1024M');

        $this->w = new Workflows();

        $q = $this->w->read($this->query_file . '.json');
        $this->queries = $q ? (array)$q : [];
    }

    public function __destruct()
    {
        $this->w->write($this->queries, $this->query_file . '.json');
    }

    public function get_query_data($id, $query, $url)
    {
        if (!$query) {
            return [];
        }
        return $this->w->request($url);
    }

    public function get_db($id)
    {
        if (!array_key_exists($id, $this->dbs)) {
            return [];
        }

        $name = $id;

        $pkgs = $this->w->read($name . '.json');
        $timestamp = $this->w->filetime($name . '.json');
        if (!$pkgs || $timestamp < (time() - $this->cache_age * 86400)) {
            $data = $this->w->request($this->dbs[$id]);

            // clean jsonp wrapper
            if (substr($this->dbs[$id], -5) === 'jsonp') {
                $data = preg_replace('/.+?([\[{].+[\]}]).+/', '$1', $data);
            }

            $this->w->write($data, $name . '.json');
            $pkgs = json_decode($data);
        } elseif (!$pkgs) {
            $pkgs = [];
        }


        return $pkgs;
    }

    public function get_query_json($id, $query, $url)
    {
        if (!$query) {
            return [];
        }

        $name = $id . '.' . $query;

        $pkgs = $this->w->read($name . '.json');
        $timestamp = $this->w->filetime($name . '.json');
        if (!$pkgs || $timestamp < (time() - $this->cache_age * 86400)) {
            $data = $this->w->request($url);

            // clean jsonp wrapper
            if (substr($url, -5) === 'jsonp') {
                $data = preg_replace('/.+?([\[{].+[\]}]).+/', '$1', $data);
            }

            $this->w->write($data, $name . '.json');
            $this->queries[$name] = time();
            $pkgs = json_decode($data);
        } elseif (!$pkgs) {
            $pkgs = [];
        }

        return $pkgs;
    }

    public function get_query_regex($id, $query, $url, $regex, $regex_pos = 1)
    {
        if (!$query) {
            return [];
        }

        $name = $id . '.' . $query;

        $pkgs = $this->w->read($name . '.json');
        $timestamp = $this->w->filetime($name . '.json');

        if (!$pkgs || $timestamp < (time() - $this->cache_age * 86400)) {
            $data = $this->w->request($url);

            preg_match_all($regex, $data, $matches);
            $data = $matches[$regex_pos];

            $this->w->write($data, $name . '.json');
            $pkgs = is_string($data) ? json_decode($data) : $data;
            $this->queries[$name] = time();
        } elseif (!$pkgs) {
            $pkgs = [];
        }

        return $pkgs;
    }

    public function get_query_raw($id, $query, $url)
    {
        if (empty($query)) {
            return [];
        }

        $name = $id . '.' . $query;

        $pkgs = $this->w->read($name . '.json');
        $timestamp = $this->w->filetime($name . '.json');

        if ($pkgs === false || $timestamp < (time() - $this->cache_age * 86400)) {
            $data = $this->w->request($url);
            $this->w->write($data, $name . '.json');

            $pkgs = $data;

            $this->queries[$name] = time();

            return $pkgs;
        }

        return $pkgs;
    }

    public function update_db($id)
    {
        $url = $this->dbs[$id];
        if (is_array($url)) {
            foreach ($url as $index => $u) {
                $part = $this->w->request($u);
                $this->w->write($part, "$id-$index.json");
                $data[] = $part;
            }

            return $data;
        }

        $data = $this->w->request($url);

        // clean jsonp wrapper
        if (strpos($url, 'jsonp') !== false) {
            $data = preg_replace('/.+?({.+}).+/', '$1', $data);
        }

        $this->w->write($data, $id . '.json');
        return $data;
    }

    public function clear()
    {
        // remove db json files
        foreach ($this->dbs as $key => $url) {
            if (is_array($url)) {
                array_map(function ($part) use ($key) {
                    $this->w->delete("$key-$part.json");
                }, array_keys($url));
            } else {
                $this->w->delete($key . '.json');
            }
        }

        // remove query json files
        foreach ($this->queries as $key => $timestamp) {
            $this->w->delete($key . '.json');
        }
        $this->queries = [];
    }
}
