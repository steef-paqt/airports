<?php

namespace Steefdw\Airports;

use Carbon\Carbon;

final readonly class Airport
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        public string $icao,
        public ?string $iata,
        public string $name,
        public string $city,
        public string $state,
        public string $country,
        public int $elevation,
        public float $lat,
        public float $lon,
        public string $timezone,
    ) {
    }

    public function getDistance(self $airport): float
    {
        return $this->calculateDistance($this->lat, $this->lon, $airport->lat, $airport->lon);
    }

    public function getTimeDiff(self $airport): float
    {
        $here = Carbon::now($this->timezone);
        $there = Carbon::now($airport->timezone);

        $hourDiff = round($here->diffInMinutes($there->toDateTimeString(), true)) / 60;
        $isBefore = $there->isBefore($here->toDateTimeString());

        return $isBefore ? -$hourDiff : $hourDiff;
    }

    /**
     * This routine calculates the distance between two points (given the latitude/longitude of those points).
     * It is being used to calculate the distance between two locations using GeoDataSource(TM) Products.
     *
     * @see https://www.geodatasource.com/developers/php
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2, string $unit = 'K'): float
    {
        if (($lat1 === $lat2) && ($lon1 === $lon2)) {
            return 0;
        }

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit === 'K') {
            return $miles * 1.609344;
        }
        if ($unit === 'N') {
            return $miles * 0.8684;
        }

        return $miles;
    }
}
