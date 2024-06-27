<?php

namespace Steefdw\Airports;

final class Airports
{
    /**
     * @var ?array<string, array{
     *    icao: string,
     *    iata: string,
     *    name: string,
     *    city: string,
     *    state: string,
     *    country: string,
     *    elevation: int,
     *    lat: float,
     *    lon: float,
     *    timezone: string
     *  }>
     */
    public static ?array $airports = null;

    /**
     * @return array<string, array<string, string|int|float>>
     */
    public static function getAirports(): array
    {
        if (!self::$airports) {
            $json = file_get_contents(dirname(__DIR__) . '/vendor/mwgg/airports/airports.json');
            $json = str_replace('    "tz": ', '    "timezone": ', $json);
            self::$airports = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        }

        return self::$airports;
    }

    /**
     * Only get the airports that have a three-letter IATA code. These are mostly the larger airports.
     *
     * @return array<string, array<string, string|int|float>>
     */
    public static function getIataAirports(): array
    {
        $list = [];
        foreach (self::getAirports() as $airport) {
            if ($airport['iata']) {
                $list[$airport['iata']] = $airport;
            }
        }

        return $list;
    }

    public static function getAirport(string $code): ?Airport
    {
        $code = strtoupper($code);
        $airport = match (strlen($code)) {
            3       => self::getIataAirports()[$code] ?? null,
            4       => self::getAirports()[$code] ?? null,
            default => throw new \UnexpectedValueException("Not a valid IATA / ICAO code: '$code'")
        };

        if ($airport) {
            return new Airport(...$airport);
        }

        return null;
    }

    /**
     * @return array<string, array<string, string|int|float>>
     */
    public static function getAirportsByCountryCode(string $code): array
    {
        $code = strtoupper($code);
        if (strlen($code) !== 2) {
            throw new \UnexpectedValueException("Not a valid alpha2 country code: '$code'");
        }

        $list = [];
        foreach (self::getAirports() as $airport) {
            if ($airport['country'] === $code) {
                $list[$airport['icao']] = $airport;
            }
        }

        return $list;
    }
}
