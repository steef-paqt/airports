<?php

namespace Steefdw\Airports;

final class Airports
{
    /**
     * @var ?array<string, Airport>
     */
    public static ?array $airports = null;

    /**
     * @return array<string, Airport>
     */
    public static function getAirports(): array
    {
        if (!self::$airports) {
            $json = self::getDataJson();
            $airports = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            foreach ($airports as $code => $airport) {
                self::$airports[$code] = new Airport(...$airport);
            }
        }

        return self::$airports;
    }

    /**
     * Only get the airports that have a three-letter IATA code. These are mostly the larger airports.
     *
     * @return array<string, Airport>
     */
    public static function getIataAirports(): array
    {
        $list = [];
        foreach (self::getAirports() as $airport) {
            if ($airport->iata) {
                $list[$airport->iata] = $airport;
            }
        }

        return $list;
    }

    public static function getAirport(string $code): ?Airport
    {
        $code = strtoupper($code);

        return match (strlen($code)) {
            3       => self::getIataAirports()[$code] ?? null,
            4       => self::getAirports()[$code] ?? null,
            default => throw new \UnexpectedValueException("Not a valid IATA / ICAO code: '$code'")
        };
    }

    /**
     * @return array<string, Airport>
     */
    public static function getAirportsByCountryCode(string $code): array
    {
        $code = strtoupper($code);
        if (strlen($code) !== 2) {
            throw new \UnexpectedValueException("Not a valid alpha2 country code: '$code'");
        }

        $list = [];
        foreach (self::getAirports() as $airport) {
            if ($airport->country === $code) {
                $list[$airport->icao] = $airport;
            }
        }

        return $list;
    }

    private static function getDataJson(): string
    {
        $filePath = dirname(__DIR__) . '/data/airports.json';
        if (!file_exists($filePath)) {
            $json = file_get_contents('https://github.com/mwgg/Airports/raw/master/airports.json');
            file_put_contents($filePath, $json);
        } else {
            $json = file_get_contents($filePath);
        }

        return (string) str_replace('    "tz": ', '    "timezone": ', $json);
    }
}
