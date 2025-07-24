<?php
require 'base.php';

$result = simplexml_load_string($azure->request(
    'GET',
    '',
    [
        'comp' => 'list',
        'restype' => 'container'
    ]
));

$blobs = [];
foreach($result->Blobs->Blob as $blob){
    $blobs[] = $blob;
}

usort($blobs, function($a, $b){
    $getDate = function($blob){
        return new DateTime($blob->Properties->{'Last-Modified'});
    };

    return $getDate($a) < $getDate($b);
});

$newestBlob = $blobs[0];
$content = $azure->request(
    'GET',
    $newestBlob->Name,
    []
);

header('Content-Disposition: attachment; filename="' . $newestBlob->Name . '"');
echo $content;