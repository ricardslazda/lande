<?php

declare(strict_types=1);

namespace App\Http\Services\LoanSchedule;

use App\Helpers\Mathematical as MathematicalHelper;
use App\Http\Structures\Method\LoanScheduleCalculateArgumentStructure;
use App\Http\Structures\Segment;

class Calculation
{
    private const MONTHS_IN_YEAR = 12;

    /**
     * @param LoanScheduleCalculateArgumentStructure $parameters
     * @return Segment[]
     */
    public function calculateLoanScheduleSegments(LoanScheduleCalculateArgumentStructure $parameters): array
    {
        $segments = [];

        $totalYearlyInterestInPercent = $parameters->getBaseInterestInPercent() + $parameters->getEuriborInterestInPercent();
        $monthlyInterestInPercent = $totalYearlyInterestInPercent / self::MONTHS_IN_YEAR;
        $loanAmountInCents = $parameters->getLoanAmountInCents();

        $baseInterestSplit = $parameters->getBaseInterestInPercent() / $totalYearlyInterestInPercent;
        $euriborInterestSplit = $parameters->getEuriborInterestInPercent() / $totalYearlyInterestInPercent;

        $monthlyTotalPaymentInCents = $this->calculateMonthlyPayment($monthlyInterestInPercent, $loanAmountInCents, $parameters->getNumberOfPaymentsInMonths());

        for ($i = 0; $i < $parameters->getNumberOfPaymentsInMonths(); $i++) {
            $totalInterestInCents = $loanAmountInCents * $monthlyInterestInPercent;
            $monthlyPrincipalInCents = $monthlyTotalPaymentInCents - $totalInterestInCents;
            $loanAmountInCents = $loanAmountInCents - $monthlyPrincipalInCents;

            $adjustedEuriborInPercent = $this->getEuriborAdjustment($parameters->getEuriborAdjustments(), $i);

            if ($adjustedEuriborInPercent) {
                $euriborInterestSplit = $adjustedEuriborInPercent / $totalYearlyInterestInPercent;
            }

            $segment = new Segment();
            $segment->segmentNumber = $i + 1;
            $segment->principalPaymentInCents = (int)$monthlyPrincipalInCents;
            $segment->interestPaymentInCents = (int)($totalInterestInCents * $baseInterestSplit);
            $segment->euriborPaymentInCents = (int)($totalInterestInCents * $euriborInterestSplit);
            $segment->totalPaymentInCents = (int)$monthlyTotalPaymentInCents;
            $segment->remainingPrincipalInCents = (int)$loanAmountInCents;

            $segments[] = $segment;
        }

        return $segments;
    }

    private function calculateMonthlyPayment(float $monthlyInterestInPercent, int $loanAmountInCents, int $numberOfPaymentsInMonths): float
    {
        return ($monthlyInterestInPercent * $loanAmountInCents) / (1 - (pow((1 + $monthlyInterestInPercent), -$numberOfPaymentsInMonths)));
    }

    private function getEuriborAdjustment(array $euriborAdjustments, int $segmentNumber): float|int|null
    {
        if (!empty($euriborAdjustments)) {
            if (isset($euriborAdjustments[$segmentNumber])) {
                return MathematicalHelper::convertPointsToPercent($euriborAdjustments[$segmentNumber]);
            }
        }

        return null;
    }
}
