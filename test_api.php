<?php
$url = 'http://localhost:8000/api/user';
$ch  = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo 'HTTP Code: ' . $httpCode . PHP_EOL;
echo 'Response: ' . substr($response, 0, 200) . PHP_EOL;
