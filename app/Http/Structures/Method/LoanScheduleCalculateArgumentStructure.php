<?php

declare(strict_types=1);

namespace App\Http\Structures\Method;

use JetBrains\PhpStorm\Pure;

class LoanScheduleCalculateArgumentStructure
{
    /** Simple associate array */
    private array $euriborAdjustments = [];
    private float $baseInterestInPercent;
    private float $euriborInterestInPercent;
    private int $loanAmountInCents;
    private int $numberOfPaymentsInMonths;

    public function getEuriborAdjustments(): array
    {
        return $this->euriborAdjustments;
    }

    public function setEuriborAdjustments(array $euriborAdjustments): void
    {
        $this->euriborAdjustments = $euriborAdjustments;
    }

    public function getBaseInterestInPercent(): float|int
    {
        return $this->baseInterestInPercent;
    }

    public function setBaseInterestInPercent(float $baseInterestInPercent): void
    {
        $this->baseInterestInPercent = $baseInterestInPercent;
    }

    public function getEuriborInterestInPercent(): float|int
    {
        /** It is possible that Euribor is negative, in those cases we should treat it as zero. */
        if ($this->euriborInterestInPercent < 0) {
            return 0;
        }

        return $this->euriborInterestInPercent;
    }

    public function setEuriborInterestInPercent(float $euriborInterestInPercent): void
    {
        $this->euriborInterestInPercent = $euriborInterestInPercent;
    }

    public function getLoanAmountInCents(): int
    {
        return $this->loanAmountInCents;
    }

    public function setLoanAmountInCents(int $loanAmountInCents): void
    {
        $this->loanAmountInCents = $loanAmountInCents;
    }

    public function getNumberOfPaymentsInMonths(): int
    {
        return $this->numberOfPaymentsInMonths;
    }

    public function setNumberOfPaymentsInMonths(int $numberOfPaymentsInMonths): void
    {
        $this->numberOfPaymentsInMonths = $numberOfPaymentsInMonths;
    }
}
