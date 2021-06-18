<?php
namespace WillFarrell\AlfredPkgMan;

class DefinitelyTyped extends Repo
{
    protected $id         = 'dt';
    protected $url        = 'https://definitelytyped.org';
    protected $search_url = 'https://api.npms.io/v2/search';
    protected $npm_scope  = 'types';

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->asJson();
        }

        $this->pkgs = $this->cache->get_query_json(
            $this->id,
            $query,
            "{$this->search_url}?q=scope:{$this->npm_scope}+{$query}&size={$this->max_return}"
        );

        foreach ($this->pkgs->results as $pkg) {
            $p = $pkg->package;
            $scope_prefix = "@{$this->npm_scope}/";
            $is_deprecated = !empty($pkg->flags->deprecated);
            $name = $is_deprecated ? str_replace($scope_prefix, "", $p->name) : $p->name;
            $description = $is_deprecated ? "[!] {$p->name} has been deprecated: {$pkg->flags->deprecated}" : $p->description;
            $link = $is_deprecated ? str_replace(urlencode($scope_prefix), "", $p->links->npm) : $p->links->npm;

            $this->cache->w->result(
                $this->id,
                $this->makeArg($name, $link, "{$p->name}: {$p->version}"),
                $name,
                $description,
                "icon-cache/{$this->id}.png"
            );

            // only search till max return reached
            if (count($this->cache->w->results()) === $this->max_return) {
                break;
            }
        }

        $this->noResults($query, "https://www.typescriptlang.org/dt/search?search={$query}");

        return $this->asJson();
    }
}

// Test code, uncomment to debug this script from the command-line
// $repo = new DefinitelyTyped();
// echo $repo->search('react');
