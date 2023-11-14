<?php

declare(strict_types=1);

namespace App\Helpers;

class Mathematical
{
    public static function convertPointsToPercent(int|string $points): float|int
    {
        return (int)$points / 10000;
    }
}
