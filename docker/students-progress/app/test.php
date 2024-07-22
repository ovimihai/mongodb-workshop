<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

if (!isset($_GET['f'])) {
    die('.');
}

foreach ($mongoHosts as $hostNo =>$host) {

    for($i=1; $i<=$maxDbNo; $i++) {
        $dbName = 'db'.$i;
        $mdbUri = 'mongodb://user' . $i . ':pass' . $i . '@' . $host . ':' . $mongoPort.'/?appname=phpAppTest&authSource=' . $dbName;

        try{
            $client = new MongoDB\Client($mdbUri);
        } catch (MongoDB\Driver\Exception\Exception $e) {
            var_dump($e->getMessage());
        }

        $profile = $client->selectCollection($dbName, 'abc');
        $userQueries = $profile->find(['a' => rand(0, 100)]);
    }
}
