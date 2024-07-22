<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

date_default_timezone_set($conf['timezone']);
$tzo = new DateTimeZone($conf['timezone']);

$interval = 10;
if (isset($_GET['interval'])) {
    $interval = intval($_GET['interval']);
}


$msAgo = $interval*60*1000;
$timeLimit = $crtTimeMs - $msAgo;
$mongoTimeLimit = new MongoDB\BSON\UTCDateTime( $timeLimit );
echo "Queries since: " .  $mongoTimeLimit->toDateTime()->setTimezone($tzo)->format('Y-m-d H:i:s');

$query = [
    "op"=> "query",
//    "ns"=> ['$nin' => ["db15.system.profile", "db15.system.js"]],
    "ns" => ['$not' => new MongoDB\BSON\Regex ( 'system')],
    "ts"=> ['$gt'=> $mongoTimeLimit]
];

$queryList = [];

foreach ($mongoHosts as $hostNo =>$host) {
    try{
        $client = new MongoDB\Client('mongodb://'.$conf['student']['adminLogin'].'@'.$host.':'.$mongoPort);
    } catch (MongoDB\Driver\Exception\Exception $e) {
        var_dump($e->getMessage());
        continue;
    }
    for($i=1; $i<=$maxDbNo; $i++){
        $dbName = 'db' . $i;
        $profile = $client->selectCollection($dbName, 'system.profile');
        $userQueries = $profile->find($query);
        foreach ($userQueries as $q) {
            $queryList[] = [
                "host" => $hostNo,
                "db" => $i,
                "collection" => $q->command->find,
                "query" => json_encode($q->command->filter),
                "nreturned" => $q->nreturned,
                "responseLength" => $q->responseLength,
                "millis" => $q->millis,
                "appName" => $q->appName,
                "client" => $q->client,
                "user" => $q->user,
                "ts" => $q->ts->toDateTime()->setTimezone($tzo)->format('H:i:s')
            ];
        }
    }
}
echo "<br />Queries count: " . count($queryList);

if (count($queryList) > 0) {

    try{
        $client = new MongoDB\Client('mongodb://admin:adminabcd@'.$conf['teacher']['host'].':'.$conf['teacher']['port']);
        $students = $client->selectCollection('workshop', 'students');
        $studentsQuery = $students->find([]);
        $students = [];

        foreach ($studentsQuery as $student) {
            $students[$student['host']][$student['db']] = $student;
        }
        foreach ($queryList as &$q) {
            if (isset($students[$q['host']][$q['db']])) {
                $q['student'] = $students[$q['host']][$q['db']]['name'];
                $q['position'] = $students[$q['host']][$q['db']]['position'];
            }
        }


    } catch (MongoDB\Driver\Exception\Exception $e) {
        var_dump($e->getMessage());
    }

    echo "<table>";
    echo "<tr>";
    echo "<th>Host</th><th>DB</th><th>Collection</th><th>Query</th><th>N Returned</th><th>Resp Length</th><th>ms</th><th>Application</th><th>Version</th><th>User</th><th>Time</th>";
    echo "<th>Student</th><th>Position</th>";
    echo "</tr>";
    foreach ($queryList as $q) {
        echo "<tr>";
        echo "<td>".htmlspecialchars($q['host'])."</td>";
        echo "<td>".htmlspecialchars($q['db'])."</td>";
        echo "<td>".htmlspecialchars($q['collection'])."</td>";
        echo "<td>".htmlspecialchars($q['query'])."</td>";
        echo "<td>".intval($q['nreturned'])."</td>";
        echo "<td>".intval($q['responseLength'])."</td>";
        echo "<td>".intval($q['millis'])."</td>";
        echo "<td>".htmlspecialchars($q['appName'])."</td>";
        echo "<td>".htmlspecialchars($q['client'])."</td>";
        echo "<td>".htmlspecialchars($q['user'])."</td>";
        echo "<td>".$q['ts']."</td>";
        echo "<td>".htmlspecialchars($q['student'])."</td>";
        echo "<td>".htmlspecialchars($q['position'])."</td>";
        echo "</tr>";
    }
    echo "</table>";
}

?>
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td {
        padding: 3px;
    }
</style>
