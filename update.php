<?php

echo 'Downloading airports.json...';
$json = file_get_contents('https://github.com/mwgg/Airports/raw/master/airports.json');

$target = __DIR__ . '/data/airports.json';
if ($json && file_put_contents($target, $json) !== false) {
    echo ' done' . PHP_EOL;
} else {
    echo ' failed' . PHP_EOL;
}

