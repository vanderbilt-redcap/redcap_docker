<?php
require 'base.php';

echo 'Creating azure container...';
$azure->request(
    'PUT',
    '',
    [
        'restype' => 'container'
    ],
    [
        201, // success
        409, // already exists
    ]
);

echo 'success\n';