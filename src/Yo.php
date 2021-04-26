<?php
namespace WillFarrell\AlfredPkgMan;


class Yo extends Repo
{
    protected $id         = 'yo';
    protected $kind       = 'generators';
    protected $has_db     = true;

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->asJson();
        }

        foreach ($this->pkgs->results as $pkg) {
            // make params
            $pkg = $pkg->package;
            if ($this->check($pkg, $query)) {
                $title = $pkg->name;

                // add author to title
                if (isset($pkg->author->name)) {
                    $title .= " by {$pkg->author->name}";
                }

                $this->cache->w->result(
                    $pkg->name,
                    $this->makeArg($pkg->name, $pkg->website),
                    $title,
                    $pkg->description,
                    "icon-cache/{$this->id}.png"
                );
            }

            // only search till max return reached
            if (count($this->cache->w->results()) === $this->max_return) {
                break;
            }
        }

        $this->noResults($query, $this->search_url);

        return $this->asJson();
    }
}

// Test code, uncomment to debug this script from the command-line
// $repo = new Yo();
// echo $repo->search('ang');
