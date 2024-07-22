<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

if (isset($_GET['profiling'])) {
    $profiling = intval($_GET['profiling']);

    if (in_array($profiling, [0,1])) {
        foreach ($mongoHosts as $hostNo =>$host) {

            for($i=1; $i<=$maxDbNo; $i++) {
                $dbName = 'db'.$i;
                $mdbUri = 'mongodb://user' . $i . ':pass' . $i . '@' . $host . ':' . $mongoPort.'/?appname=phpAppTest&authSource=' . $dbName;

                try{
                    $client = new MongoDB\Client($mdbUri);
                } catch (MongoDB\Driver\Exception\Exception $e) {
                    var_dump($e->getMessage());
                }

                $client->$dbName->command(['profile' => $profiling,
                    "slowms"=> 1,
                    "sampleRate"=> 1,
                    "filter"=> [
                        "op"=> "query",
                        "command.find"=> ['$ne'=> "system.profile"]
                    ]
                ]);

                if (($profiling == 0) && isset($_GET['cleanup'])) {
                    $client->$dbName->dropCollection('system.profile');
                }
            }
        }
        echo "Turned Profiling to: " . $profiling;
    }
}


