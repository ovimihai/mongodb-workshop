<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

try{
    $client = new MongoDB\Client('mongodb://admin:adminabcd@'.$conf['teacher']['host'].':'.$conf['teacher']['port']);
    $students = $client->selectCollection('workshop', 'students');
    $studentsQuery = $students->find([]);
    $students = [];

    foreach ($studentsQuery as $student) {
        $students[] = $student;
    }


} catch (MongoDB\Driver\Exception\Exception $e) {
    var_dump($e->getMessage());
}

echo "<table>";
echo "<tr>";
echo "<th>Student</th><th>Position</th><th>Link</th>";
echo "</tr>";
foreach ($students as $s) {
    echo "<tr>";
    echo "<td><a href='sip:".htmlspecialchars($s['email'])."'>".htmlspecialchars($s['name'])."</a></td>";
    echo "<td>".htmlspecialchars($s['position'])."</td>";
    $link = 'mongodb://user'.$s['db'].':pass'.$s['db'].'@'.$conf['student']['hosts'][$s['host']].':'.$conf['student']['port'];
    echo "<td>".htmlspecialchars($link)."</td>";
    echo "</tr>";
}
echo "</table>";


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
