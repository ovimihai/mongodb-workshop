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

$query = [
    "op"=> "query",
//    "ns"=> ['$nin' => ["db15.system.profile", "db15.system.js"]],
    "ns" => ['$not' => new MongoDB\BSON\Regex ( 'system')],
    "ts"=> ['$gt'=> $mongoTimeLimit]
];

$queryList = [];
$queryCounts = [];

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
            $id = intval(str_pad($hostNo, 2, '0', STR_PAD_LEFT)
                . str_pad($i, 2, '0', STR_PAD_LEFT));

            $queryList[] = [
                "id" => $id,
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

            $minute = round($q->ts->toDateTime()->setTimezone($tzo)->format('His') / 60);
            $queryCounts[$id][$minute] += 1;
        }
    }
}

$qcPlot = [];
foreach ($queryCounts as $key => $value) {
    $qcPlot[] = [
            'x' =>  array_keys($value),
            'y' => array_values($value),
            'type' => 'scatter',
            'name' => $key
        ];
}
?><!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <script src="https://cdn.plot.ly/plotly-2.34.0.min.js" charset="utf-8"></script>
</head>
<body>
<?php
echo "Queries since: " .  $mongoTimeLimit->toDateTime()->setTimezone($tzo)->format('Y-m-d H:i:s');
echo "<br />Queries count: " . count($queryList);
?>
<div id="myDiv"></div>
<?php

if (count($queryList) > 0) {

    try{
        $client = new MongoDB\Client('mongodb://admin:adminabcd@'.$conf['teacher']['host'].':'.$conf['teacher']['port']);
        $students = $client->selectCollection('workshop', 'students');
        $studentsQuery = $students->find([]);
        $students = [];

        foreach ($studentsQuery as $student) {
            $id = intval(str_pad($student['host'], 2, '0', STR_PAD_LEFT)
                . str_pad($student['db'], 2, '0', STR_PAD_LEFT));
            $students[$id] = $student;
        }
        foreach ($queryList as &$q) {
            if (isset($students[$q['id']])) {
                $q['student'] = $students[$q['id']]['name'];
                $q['position'] = $students[$q['id']]['position'];
            }
        }
        foreach ($qcPlot as &$qc) {
            if (isset($students[$qc['name']])) {
                $qc['name'] = $students[$qc['name']]['name'];
            }
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        var_dump($e->getMessage());
    }

    ?>
    <script type="text/javascript">
        var layout = {
            autosize: false,
            width: 1000,
            height: 300,
            margin: { l: 20 , r: 5, b: 5, t: 20, pad: 2
            },
        };
        var data = <?php echo json_encode($qcPlot); ?>;
        Plotly.newPlot('myDiv', data, layout);
    </script>
    <?php

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
</body>
