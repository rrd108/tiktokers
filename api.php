<?php

$pdo = new PDO('mysql:host=localhost;dbname=tiktokers', 'root', '123');

require('./headers.php');

$stmt = $pdo->prepare('SELECT tiktoker, followerCount, videoCount, heartCount, videoStats
    FROM stats 
    WHERE date = (SELECT MAX(date) FROM `stats`)');
$stmt->execute();
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stats = array_map(function ($stat) {
    $stat['videoStats'] = json_decode($stat['videoStats'], true);
    return $stat;
}, $stats);

echo json_encode($stats);
