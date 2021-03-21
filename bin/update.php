<?php

require_once __DIR__.'/../vendor/autoload.php';

$cache = new WillFarrell\AlfredPkgMan\Cache();

foreach ($cache->dbs as $key => $url) {
    $cache->update_db($key);
}
