<?php

echo 'Creating azure container...';

$azure_app_name = 'devstoreaccount1';
$secret_key = 'Eby8vdM02xNOcqFlqUwJPLlmEtlCDXJ1OUzFT50uSRZ6IFsuFq2UVErCz4I6tq/K1SZFPTOtr/KBHBeksoGMGw==';
$container_name = 'mycontainer';
$azure_environment = 'blob.core.windows.net';
$host = "$azure_app_name.$azure_environment";

$date = gmdate('D, d M Y H:i:s \G\M\T');
$version = "2015-04-05";

$str_to_sign = "PUT\n\n\n\n\napplication/x-www-form-urlencoded\n$date\n\n\n\n\n\nx-ms-version:" . $version . "\n/" . $azure_app_name . "/" . $container_name . "\nrestype:container";
$signature = 'SharedKey' . ' ' . $azure_app_name . ':' . base64_encode(hash_hmac('sha256', $str_to_sign, base64_decode($secret_key), true));

$header = [
	"Host: $host",
	"date: " . $date,
	"x-ms-version: " . $version,
	"Authorization: " . $signature,
"content-type: application/x-www-form-urlencoded",
];

$url = "https://$host/$container_name?restype=container";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
$result = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

function handleError($message) {
    http_response_code(500);
    die("$message\n");
}

if (!empty($error)) {
    handleError("CURL Error: $error");
}

if (!in_array($httpCode, [
    201, // success
    409, // already exists
])) {
	handleError("HTTP error code $httpCode received.");
}

echo 'success\n';