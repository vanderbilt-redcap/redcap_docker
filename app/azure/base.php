<?php

function handleError($message) {
    http_response_code(500);
    die("$message\n");
}

$azure = new class() {
    function request($method, $path, $args, $expectedResponseCodes=[200]){
        $azure_app_name = 'devstoreaccount1';
        $secret_key = 'Eby8vdM02xNOcqFlqUwJPLlmEtlCDXJ1OUzFT50uSRZ6IFsuFq2UVErCz4I6tq/K1SZFPTOtr/KBHBeksoGMGw==';
        $container_name = 'mycontainer';
        $azure_environment = 'blob.core.windows.net';
        $host = "$azure_app_name.$azure_environment";

        $date = gmdate('D, d M Y H:i:s \G\M\T');
        $version = "2015-04-05";
        $resourcePath = "$container_name";
        if($path){
            $resourcePath .= "/$path";
        }

        $str_to_sign = "$method\n\n\n\n\napplication/x-www-form-urlencoded\n$date\n\n\n\n\n\nx-ms-version:" . $version . "\n/" . $azure_app_name . "/" . $resourcePath;

        $urlArgs = [];
        foreach($args as $key=>$value){
            $str_to_sign .= "\n$key:$value";
            $urlArgs[] = "$key=$value";
        }

        $signature = 'SharedKey' . ' ' . $azure_app_name . ':' . base64_encode(hash_hmac('sha256', $str_to_sign, base64_decode($secret_key), true));

        $header = [
            "Host: $host",
            "date: " . $date,
            "x-ms-version: " . $version,
            "Authorization: " . $signature,
            "content-type: application/x-www-form-urlencoded",
        ];

        $url = "https://$host/$resourcePath?" . implode('&', $urlArgs);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if (!empty($error)) {
            handleError("CURL Error: $error");
        }

        if (!in_array($httpCode, $expectedResponseCodes)) {
            handleError("HTTP error code $httpCode received.");
        }

        return $result;
    }
};