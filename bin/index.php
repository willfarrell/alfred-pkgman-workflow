<?php

require_once __DIR__ . '/../vendor/autoload.php';

$repo = '\\WillFarrell\\AlfredPkgMan\\'.ucfirst($argv[1]);
$query = $argv[2];

/**
 * @var \WillFarrell\AlfredPkgMan\Repo
 */
$r = new $repo();
echo $r->search($query);


