<?php

$tiktokers = [
    '@csengeforstner', '@whisperton', '@lililaskai', '@vivi_es_norbi', '@adriennmakk',
    '@karica.czura', '@jazmin.hollosi', '@viviensapi', '@timiifarkas', '@milanszender',
    '@ladyszomjas', '@tinamakovics', '@fanniburjan', '@karcsy.suto', '@zsombor_radics',
    '@Szabyest', '@nonivarga',
    '@vallabhi_kantha', '@dayamaya_d', '@akrisnaslany', '@isasanga_dd', '@3yuga',
    '@rasa_twins', '@hare_krisna_bud', '@gopalifarmkonyha'
];
$stats = [];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => [
        'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'accept-encoding: gzip, deflate, br',
        'accept-language: hu-HU,hu;q=0.9',
        'sec-fetch-dest: document',
        'sec-fetch-mode: navigate',
        'sec-fetch-site: none',
        'sec-fetch-user: ?1',
        'sec-gpc: 1',
        'upgrade-insecure-requests: 1',
        'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.115 Safari/537.36',
        'cookie: tt_csrf_token=LDx7iTcK-mjVZEIRgVsVpUShBE9cVFcW041w; csrf_session_id=5e70b095b415a5c5f4c245eb6d659294; LD_T=fa33817d-568c-46d5-926f-ddcd5ea78956; cookie-consent={%22ga%22:true%2C%22af%22:true%2C%22fbp%22:true%2C%22lip%22:true%2C%22bing%22:true%2C%22ttads%22:true%2C%22reddit%22:true%2C%22version%22:%22v8%22}; passport_csrf_token=2ce8483cd2c97b2785fd3a224ab9ac77; passport_csrf_token_default=2ce8483cd2c97b2785fd3a224ab9ac77; s_v_web_id=verify_l5m8p9gt_GDDOb0FH_TTx8_4BOl_AQen_N4e7wyHbwmgA; passport_auth_status=66c2967982ca89358b725023d16b7b8c%2C; passport_auth_status_ss=66c2967982ca89358b725023d16b7b8c%2C; __tea_cache_tokens_1988={%22user_unique_id%22:%227109124279853860357%22%2C%22timestamp%22:1657953401765%2C%22_type_%22:%22default%22}; _abck=9AFC4419B09CC591B7D8BF7AB09DDF3B~-1~YAAQZQUuH1gxnBiCAQAAJNkCGwj5vG5EeVmR4HI+OqKFcykTbm03EZ+kcb265Q1qU/OHmHYwYb3XHD/pQW4VlEZ3ZeJWbll2OiNlyKyPJaMTdi6jbiA2XIj2ppYxV25eJuyzDHQioNyDcg0HaSMtVaKdDQfLHrxgcT6QuOfC8h1Kd6HJELShnQx2M9CMnvT+0ObN+psFYuilbbdlHycQs8ldzOeTVDig6XSpN2SXGpMP4tGoj4sDmwBbkotI5Y9mcM4JqTjVMCdBWrhQY3JKc3cL6FnDAV/IvIGeioMoS55PsDMn+x8q9HuU3j+/OYqx1VvSENMQYMZ+KxfwgQF/gBVg1P7Jqk8wcVp7dX3cvBADp2aYIRLeaTcxx5LiPTuz+HpTrvbRY0bE~-1~-1~-1; sid_guard=851f08fcfc6f60d9bb1d020c225c5eee%7C1658312744%7C5184000%7CSun%2C+18-Sep-2022+10%3A25%3A44+GMT; uid_tt=cdb963f6abb7179a365a02d7c38f9b589b5317812811e6a52a55814604e17c0b; uid_tt_ss=cdb963f6abb7179a365a02d7c38f9b589b5317812811e6a52a55814604e17c0b; sid_tt=851f08fcfc6f60d9bb1d020c225c5eee; sessionid=851f08fcfc6f60d9bb1d020c225c5eee; sessionid_ss=851f08fcfc6f60d9bb1d020c225c5eee; sid_ucp_v1=1.0.0-KDg5NmMzZDZhNDk5YjFkYzJhNTZhZTFkODM2MDgxYTA0NWY5MjU5NzkKIAiFiKP4uYfC7mEQqLDflgYYswsgDDCUsfWOBjgEQOoHEAMaBm1hbGl2YSIgODUxZjA4ZmNmYzZmNjBkOWJiMWQwMjBjMjI1YzVlZWU; ssid_ucp_v1=1.0.0-KDg5NmMzZDZhNDk5YjFkYzJhNTZhZTFkODM2MDgxYTA0NWY5MjU5NzkKIAiFiKP4uYfC7mEQqLDflgYYswsgDDCUsfWOBjgEQOoHEAMaBm1hbGl2YSIgODUxZjA4ZmNmYzZmNjBkOWJiMWQwMjBjMjI1YzVlZWU; store-idc=maliva; store-country-code=hu; tt-target-idc=useast1a; passport_fe_beating_status=true; cmpl_token=AgQQAPOdF-RMpbHj5pwXOh08-Mk37ktUf4cWYMYj4g; ttwid=1%7CtkwO1FarmImq-44oB4mNW0_xoEiKhDp_INhT-PKj1SM%7C1658313032%7C3085846bc10ceba4931aff10fcb8e868b2dfab7e159bff5fdfb88bf3a023f7d8; msToken=3oTfZ7FAi4uyihiA1a2bOgBzyi8QnBGu6_N9xQygZ7Z-s__Ym4HG4KtsUiUtJ3iIeglMdPaZQJJYSVsiFr9dcSgDBcjpPkcnOXmk1sik37eshxjSYWswYN9ZYbQquugGI5ggbCnQ; odin_tt=70a30278172f9fca603a74ccf92dd1f30bcfd36ed3a4d79c196e47bf4d759119ef5590f1ad99b35f9b1d72d3489aef815292ea71ee2a032129bbc25a8b02410811d1c2c76b738ab517c55a345a9a2abe; msToken=gqLUor2aOGetsNVheOGy7rkOe4n8m09Nj8S_HACejtYJd6KqbZuNwLBpq-8wLUSRA58InUFJVogEEYia9hXB-hTHa-sUx6ZgBTuRJbb05NVqbsbHAyWs'
    ],
]);

foreach ($tiktokers as $i => $tiktoker) {
    curl_setopt($ch, CURLOPT_URL, 'https://www.tiktok.com/' . $tiktoker);
    $output = curl_exec($ch);
    //echo $output;
    preg_match(
        '/"followerCount":(\d*),/',
        $output,
        $followerCount
    );
    preg_match(
        '/"heartCount":(\d*),/',
        $output,
        $heartCount
    );
    preg_match(
        '/"videoCount":([1-9][0-9]*),/',
        $output,
        $videoCount
    );
    preg_match_all(
        '/"stats":({"dig[^}]*}),/',
        $output,
        $videoStats
    );

    $stats[$tiktoker] = [
        'followerCount' => $followerCount[1],
        'heartCount' => $heartCount[1],
        'videoCount' => $videoCount[1],
        'videoStats' => json_encode(array_slice($videoStats[1], 0, 10)),
    ];
}

curl_close($ch);

//var_dump($stats);

$pdo = new PDO('mysql:host=localhost;dbname=tiktokers', 'root', '123');

foreach ($stats as $tiktoker => $data) {
    $stmt = $pdo->prepare("INSERT INTO stats (tiktoker, date, followerCount, heartCount, videoCount, videoStats) VALUES (?,?,?,?,?,?)");
    $stmt->bindValue(1, $tiktoker);
    $stmt->bindValue(2, date('Y-m-d'));
    $stmt->bindValue(3, $data['followerCount']);
    $stmt->bindValue(4, $data['heartCount']);
    $stmt->bindValue(5, $data['videoCount']);
    $stmt->bindValue(6, $data['videoStats']);
    $stmt->execute();
    echo $tiktoker . ': ' . $data['followerCount'] . ' / ' . $data['heartCount'] . ' / ' . $data['videoCount'] . ' * ' . substr($data['videoStats'], 0, 25) . "\n";
}
