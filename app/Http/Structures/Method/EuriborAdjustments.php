<?php

declare(strict_types=1);

namespace App\Http\Structures\Method;

class EuriborAdjustments
{
    /**
     * @var EuriborAdjustment[]
     */
    private array $euriborAdjustments = [];

    public function __construct($initial)
    {
        $this->euriborAdjustments = $initial;
    }

    public function getArray(): array
    {
        $this->sortStack();

        return $this->euriborAdjustments;
    }

    public function getFirst(): ?EuriborAdjustment
    {
        if (!empty($this->euriborAdjustments)) {
            return reset($this->euriborAdjustments);
        }

        return null;
    }

    private function sortStack(): void
    {
        usort($this->euriborAdjustments, static fn(EuriborAdjustment $a, EuriborAdjustment $b) => $a->startingSegmentIndex > $b->startingSegmentIndex);
    }
}
