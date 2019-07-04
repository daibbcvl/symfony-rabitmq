<?php

namespace App\Entity;

class Continent
{
    const AFRICA = 'Africa';
    const AMERICAS = 'Americas';
    const ASIA = 'Asia';
    const EUROPE = 'Europe';
    const OCEANIA = 'Oceania';
    const POLAR = 'Polar';

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::AFRICA,
            self::AMERICAS,
            self::ASIA,
            self::EUROPE,
            self::OCEANIA,
            self::POLAR,
        ];
    }
}
