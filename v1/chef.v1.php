<?php
// ****************
error_reporting(0);

$min_query_length = 3; // use when loading in large DBs

require_once('cache.php');
require_once('workflows.php');

$cache     = new Cache();
$w         = new Workflows();
$query     = urlencode('{query}');
$chef_icon = 'icon-cache/chef.png';

$cookbooks = $cache->get_query_json(
	'chef',
	$query,
	"https://supermarket.getchef.com/api/v1/search?q={$query}"
);

/**
 * Checks a cookbook item from a Supermarket search to see if it matches the
 * given query
 *
 * @param  array  $cookbook The cookbook item being checked
 * @param  string $query    The string being searched for
 * @return boolean          True if it matches; false otherwise
 */
function search($cookbook, $query)
{
	$found = false;

	if (   strpos($cookbook->cookbook_name, $query) !== false
		|| strpos($cookbook->cookbook_description, $query) !== false
	) {
		$found = true;
	}

	return $found;
}

foreach ($cookbooks->items as $cookbook) {
	if (search($cookbook, $query)) {
		$title = $cookbook->cookbook_name;

		// add author to title
		if (isset($cookbook->cookbook_maintainer)) {
			$title .= " by {$cookbook->cookbook_maintainer}";
		}

		$w->result(
			$cookbook->cookbook_name,
			"https://supermarket.getchef.com/cookbooks/{$cookbook->cookbook_name}",
			$title,
			$cookbook->cookbook_description,
			$chef_icon
		);
	}
}

if (count($w->results()) == 0) {
	if ($query) {
		$w->result(
			'chef',
			"http://supermarket.getchef.com/cookbooks/{$query}",
			"No plugins were found that matched {$query}",
			'Click to see the results for yourself',
			$chef_icon
		);
	}

	$w->result(
		'chef-www',
		'http://supermarket.getchef.com/',
		'Go to the website',
		'http://supermarket.getchef.com',
		$chef_icon
	);
}

echo $w->toxml();
// ****************
