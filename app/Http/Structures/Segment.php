<?php

namespace App\Http\Structures;

class Segment
{
    public int $segmentNumber;
    public int $principalPaymentInCents;
    public int $interestPaymentInCents;
    public int $euriborPaymentInCents;
    public int $totalPaymentInCents;
    public int $remainingPrincipalInCents;
}
