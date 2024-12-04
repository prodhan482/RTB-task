<?php
$url = 'http://localhost/RTB-task/bidreq.php';
$data = file_get_contents('bid_request.json');

if (!$data) {
    die("Failed to read bid_request.json");
}

$options = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'POST',
        'content' => $data,
    ],
];

$context  = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

if ($result === false) {
    $error = error_get_last();
    die("HTTP Request Failed: " . $error['message']);
}

echo $result;
