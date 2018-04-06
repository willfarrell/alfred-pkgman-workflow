<?php
namespace WillFarrell\AlfredPkgMan;

require_once('Repo.php');

/**
 * A template to use for creating a new repository search
 *
 * This template makes the assumption that you're searching a repo that
 * offers a JSON API endpoint.
 *
 * Look to AptGet for an example of a regex-based search (there are others).
 *
 * Additional protected variables are available if you look at Repo, if you do not
 * override them here, this class will inherit their values.
 *
 * Make sure, once you've tested your code, that the debug lines at the end are
 * commented out, or you're gonna have a confusing repo search when you package
 * it up for release.
 */
class RepoName extends Repo
{
    protected $id         = '_TEMPLATE_';
    protected $url        = '_BASE_URL_';
    protected $search_url = '_SEARCH_URL_';

    // Optional
    // protected $min_query_length = 1;
    // protected $has_db           = false; // For repos with small static DB sets. Update in Cache.php required.

    public function search($query)
    {
        if (!$this->hasMinQueryLength($query)) {
            return $this->xml();
        }

        // for repos without an API, try $this->cache->get_query_regex()
        $this->pkgs = $this->cache->get_query_json(
            $this->id,
            $query,
            "{$this->search_url}{$query}"
        );

        foreach ($this->pkgs as $pkg) {
            // make params

            $this->cache->w->result(
                $this->id,
                $this->makeArg(_PKG_NAME_, _PKG_URL_, _PKG_CONF_STR_FORMAT_),
                _PKG_NAME_,
                _PKG_DETAILS_OR_URL_,
                "icon-cache/{$this->id}.png"
            );

            // only search till max return reached
            if (count($this->cache->w->results()) == $this->max_return) {
                break;
            }
        }

        $this->noResults($query, "{$this->search_url}{$query}");

        return $this->xml();
    }
}

// Test code, uncomment to debug this script from the command-line
// $repo = new RepoName();
// $repo->search('leaflet');
