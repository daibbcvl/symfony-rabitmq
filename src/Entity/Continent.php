<?php

namespace App\Entity;

class Continent
{
    const AFRICA = 'Châu Phi';
    const AMERICAS = 'Châu Mỹ';
    const ASIA = 'Châu Á';
    const EUROPE = 'Châu Âu';
    const OCEANIA = 'Châu Úc';
    const POLAR = 'Nam Cực';

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
