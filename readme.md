# Airports

Inspired by CGP Grey's video:
[✈️ The Maddening Mess of Airport Codes! ✈️](https://www.youtube.com/watch?v=jfOUVYQnuhw)

## Installation

```bash
composer require steefdw/airports
```

## Usage

Get an airport by its IATA code:
```php
$amsterdam = Airports::getAirport('AMS');
// Steefdw\Airports\Airport^ {
//   +icao: "EHAM"
//   +iata: "AMS"
//   +name: "Amsterdam Airport Schiphol"
//   +city: "Amsterdam"
//   +state: "North-Holland"
//   +country: "NL"
//   +elevation: -11
//   +lat: 52.3086013794
//   +lon: 4.7638897896
//   +timezone: "Europe/Amsterdam"
// }
```

Get the distance between two airports:
```php
$amsterdam = Airports::getAirport('AMS');
$barcelona = Airports::getAirport('BCN');

$distance = $amsterdam->getDistance($barcelona); // 1241.0765638345 (km)
```

Get the time difference between two airports:
```php
$amsterdam = Airports::getAirport('AMS');
$london = Airports::getAirport('LTN');

$timeDiff = $amsterdam->getTimeDiff($london); // -1 (hour)
```

Get an array of airports:
```php
Airports::getAirports(); // get all ~29k airports in the world
Airports::getIataAirports(); // only the 7780 airports that have a three-letter IATA code.
Airports::getAirportsByCountryCode('nl'); // only get the 27 airports in the Netherlands
```
