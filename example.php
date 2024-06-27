<?php

namespace Steefdw\Airports;

require_once __DIR__ . '/vendor/autoload.php';

echo 'Number of airports' . PHP_EOL;
echo '- icao: ' . count(Airports::getAirports()) . PHP_EOL;
echo '- iata: ' . count(Airports::getIataAirports()) . PHP_EOL;

$amsterdam = Airports::getAirport('AMS');
$barcelona = Airports::getAirport('BCN');
$london = Airports::getAirport('LTN');

$distance = $amsterdam->getDistance($barcelona);
echo "\nDistance between '$amsterdam->name' and '$barcelona->name' is:\n$distance KM\n\n";

if ($timeDiff = $amsterdam->getTimeDiff($london)) {
    $diffType = $timeDiff > 0 ? 'later' : 'earlier';
    $hours = $timeDiff > 0 ? $timeDiff : -$timeDiff;

    echo "It is $hours hours $diffType in '$london->name' than in '$amsterdam->name'.\n\n";
} else {
    echo "It is the same time in '$london->name' as in '$amsterdam->name'.\n\n";
}

$dutchAirports = Airports::getAirportsByCountryCode('nl');
echo 'Number of Dutch airports: ' . count($dutchAirports) . PHP_EOL;
