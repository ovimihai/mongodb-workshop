<?php

require_once __DIR__ . '/vendor/autoload.php';

$confStr = file_get_contents('config.json');
$conf = json_decode($confStr, true);

$crtTimeMs = strtotime("now") * 1000;
$mongoHosts = $conf['student']['hosts'];
$mongoPort = $conf['student']['port'];
$maxDbNo=$conf['maxDbNo'];
