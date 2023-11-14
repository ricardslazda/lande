<?php

namespace App\Http\Structures\Method;

class EuriborAdjustment
{
    public int $startingSegmentIndex;
    public int $endingSegmentIndex;
    public int $adjustedEuriborInPoints;
}
