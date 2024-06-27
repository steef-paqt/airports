<?php

declare(strict_types=1);

namespace Steefdw\Airports\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Steefdw\Airports\Airport;
use Steefdw\Airports\Airports;

final class AirportsTest extends TestCase
{
    /** @test */
    public function it_should_get_all_airports(): void
    {
        $airports = Airports::getAirports();
        $this->assertIsArray($airports);
        $this->assertGreaterThan(10_000, count($airports));
    }

    /** @test */
    public function it_should_get_IATA_airports(): void
    {
        $iataAirports = Airports::getIataAirports();
        $this->assertIsArray($iataAirports);

        foreach ($iataAirports as $airport) {
            $this->assertInstanceOf(Airport::class, $airport);
            $this->assertNotEmpty($airport->iata);
        }
    }

    /** @test */
    public function it_should_get_an_airport_by_code(): void
    {
        $airport = Airports::getAirport('AMS');

        $this->assertInstanceOf(Airport::class, $airport);
        $this->assertSame('NL', $airport->country);
    }

    /** @test */
    public function it_should_get_airports_by_country(): void
    {
        $dutchAirports = Airports::getAirportsByCountryCode('nl');
        $this->assertIsArray($dutchAirports);

        foreach ($dutchAirports as $airport) {
            $this->assertInstanceOf(Airport::class, $airport);
            $this->assertSame('NL', $airport->country);
        }
    }
}
