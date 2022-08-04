<?php

$pdo = new PDO('mysql:host=localhost;dbname=tiktokers', 'root', '123');

$stmt = $pdo->prepare('SELECT * FROM stats ');
$stmt->execute();
$stats = $stmt->fetchAll();

$tiktoker_followers = $tiktoker_likes = [];

foreach ($stats as $stat) {
    $tiktoker_followers[$stat['tiktoker']][] = ['x' => $stat['date'], 'y' => $stat['followerCount']];
    $tiktoker_likes[$stat['tiktoker']][] = ['x' => $stat['date'], 'y' => $stat['heartCount']];
}

$stmt = $pdo->prepare('SELECT tiktoker, followerCount AS f, videoCount AS v, heartCount AS h, followerCount / videoCount AS fv, heartCount / videoCount AS hv, heartCount / followerCount AS hf 
    FROM stats 
    WHERE date = (SELECT MAX(date) FROM `stats`)');
$stmt->execute();
$stats = $stmt->fetchAll();
$max = ['f' => 0, 'v' => 0, 'h' => 0, 'fv' => 0, 'hv' => 0, 'hf' => 0];
$min = ['f' => 100000000, 'v' => 100000000, 'h' => 100000000, 'fv' => 100000000, 'hv' => 100000000, 'hf' => 100000000];
foreach ($stats as $stat) {
    $max['f'] = max($max['f'], $stat['f']);
    $max['v'] = max($max['v'], $stat['v']);
    $max['h'] = max($max['h'], $stat['h']);
    $max['fv'] = max($max['fv'], $stat['fv']);
    $max['hv'] = max($max['hv'], $stat['hv']);
    $max['hf'] = max($max['hf'], $stat['hf']);

    $min['f'] = min($min['f'], $stat['f']);
    $min['v'] = min($min['v'], $stat['v']);
    $min['h'] = min($min['h'], $stat['h']);
    $min['fv'] = min($min['fv'], $stat['fv']);
    $min['hv'] = min($min['hv'], $stat['hv']);
    $min['hf'] = min($min['hf'], $stat['hf']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magyar Tiktokers</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    <script src="app.js" defer></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js" defer></script>
    <script>
        window.onload = function() {
            const followerChart = new CanvasJS.Chart("followerChartContainer", {
                animationEnabled: true,
                title: {
                    text: "Magyar Tiktokers követők száma"
                },
                legend: {
                    fontSize: 12,
                    cursor: "pointer",
                    verticalAlign: "center",
                    horizontalAlign: "left",
                    dockInsidePlotArea: true,
                },
                toolTip: {
                    shared: true
                },
                data: [<?php
                        foreach ($tiktoker_followers as $tiktoker => $data) {
                            echo '{
                                type: "spline",
                                axisYType: "secondary",
                                name: "' . $tiktoker . '",
                                showInLegend: true,
                                markerSize: 0,
                                dataPoints: JSON.parse(\'' . json_encode($data) . '\').map(d => ({x: new Date(d.x), y: d.y}))
                            },';
                        }
                        ?>]
            });
            //console.log(chart);
            followerChart.render();

            const likeChart = new CanvasJS.Chart("likeChartContainer", {
                animationEnabled: true,
                title: {
                    text: "Magyar Tiktokers likeok száma"
                },
                legend: {
                    fontSize: 12,
                    cursor: "pointer",
                    verticalAlign: "center",
                    horizontalAlign: "left",
                    dockInsidePlotArea: true,
                },
                toolTip: {
                    shared: true
                },
                data: [<?php
                        foreach ($tiktoker_likes as $tiktoker => $data) {
                            echo '{
                                type: "spline",
                                axisYType: "secondary",
                                name: "' . $tiktoker . '",
                                showInLegend: true,
                                markerSize: 0,
                                dataPoints: JSON.parse(\'' . json_encode($data) . '\').map(d => ({x: new Date(d.x), y: d.y}))
                            },';
                        }
                        ?>]
            });
            //console.log(chart);
            likeChart.render();
        }
    </script>
</head>

<body>
    <div>
        <table>
            <thead>
                <tr>
                    <td>Tiktoker</td>
                    <td> <button id="orderByFollowers">↕</button> Followers</td>
                    <td>Videos</td>
                    <td>Hearts</td>
                    <td>Followers / Videos</td>
                    <td>Hearts / Videos</td>
                    <td>Hearts / Followers</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats as $i => $stat) : ?>
                    <tr>
                        <td>
                            <small> <?= $i + 1 ?></small>
                            <a href="https://www.tiktok.com/<?= $stat['tiktoker'] ?>">
                                <?= $stat['tiktoker'] ?>
                            </a>
                        </td>
                        <td class="follower r <?= $stat['f'] == $max['f'] ? 'max' : ($stat['f'] == $min['f'] ? 'min' : '') ?>">
                            <?= number_format($stat['f']) ?>
                        </td>
                        <td class="r <?= $stat['v'] == $max['v'] ? 'max' : ($stat['v'] == $min['v'] ? 'min' : '') ?>"><?= number_format($stat['v']) ?></td>
                        <td class="r <?= $stat['h'] == $max['h'] ? 'max' : ($stat['h'] == $min['h'] ? 'min' : '') ?>"><?= number_format($stat['h']) ?></td>
                        <td class="b r <?= $stat['fv'] == $max['fv'] ? 'max' : ($stat['fv'] == $min['fv'] ? 'min' : '') ?>">
                            <?= number_format($stat['fv']) ?>
                            <small>
                                <span> ▴ <?= number_format($stat['fv'] / $min['fv'] * 100) ?> %</span>
                                <span> ▾ <?= number_format($stat['fv'] / $max['fv'] * 100) ?> %</span>
                            </small>
                        </td>
                        <td class="b r <?= $stat['hv'] == $max['hv'] ? 'max' : ($stat['hv'] == $min['hv'] ? 'min' : '') ?>">
                            <?= number_format($stat['hv']) ?>
                            <small>
                                <span> ▴ <?= number_format($stat['hv'] / $min['hv'] * 100) ?> %</span>
                                <span> ▾ <?= number_format($stat['hv'] / $max['hv'] * 100) ?> %</span>
                            </small>
                        </td>
                        <td class="b r <?= $stat['hf'] == $max['hf'] ? 'max' : ($stat['hf'] == $min['hf'] ? 'min' : '') ?>">
                            <?= number_format($stat['hf']) ?>
                            <small>
                                <span> ▴ <?= number_format($stat['hf'] / $min['hf'] * 100) ?> %</span>
                                <span> ▾ <?= number_format($stat['hf'] / $max['hf'] * 100) ?> %</span>
                            </small>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="followerChartContainer" style="height: 670px; width: 100%;"></div>
    <div id="likeChartContainer" style="height: 670px; width: 100%;"></div>
</body>

</html>