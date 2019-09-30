<?php

namespace App\Entity;

class Area
{
    const NORTH = 'Miền Bắc';
    const MIDDLE = 'Miền Trung';
    const SOUTH = 'Miền Nam';

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return [
            self::NORTH,
            self::MIDDLE,
            self::SOUTH,
        ];
    }
}
