<?php

$pdo = new PDO('mysql:host=localhost;dbname=tiktokers', 'root', '123');

require('./headers.php');

if ($_GET['data'] == 'analytics') {
    $stmt = $pdo->prepare('SELECT tiktoker, followerCount, videoCount, heartCount, videoStats
    FROM stats 
    WHERE date = (SELECT MAX(date) FROM `stats`)');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = array_map(function ($stat) {
        $stat['videoStats'] = json_decode($stat['videoStats'], true);
        return $stat;
    }, $data);
}

if ($_GET['data'] == 'followers') {
    $stmt = $pdo->prepare('SELECT tiktoker, date, followerCount FROM stats');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stats = [];
    foreach ($data as $d) {
        $stats[$d['tiktoker']][] = ['date' => $d['date'], 'followerCount' => $d['followerCount']];
    }

    $data = $stats;
}

if ($_GET['data'] == 'likes') {
    $stmt = $pdo->prepare('SELECT tiktoker, date, heartCount FROM stats');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stats = [];

    foreach ($data as $d) {
        $stats[$d['tiktoker']][] = ['date' => $d['date'], 'heartCount' => $d['heartCount']];
    }

    $data = $stats;
}

echo json_encode($data);
