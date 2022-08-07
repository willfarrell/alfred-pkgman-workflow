<?php

require_once __DIR__ . '/../vendor/autoload.php';

$repo = '\\WillFarrell\\AlfredPkgMan\\'.ucfirst($argv[1]);
$query = $argc > 2 ? $argv[2]: '';


/**
 * @var \WillFarrell\AlfredPkgMan\Repo
 */
$r = new $repo();
echo $r->search($query);
