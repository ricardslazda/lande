<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Helpers\Mathematical;
use App\Http\Services\LoanSchedule\Calculation as CalculationService;
use App\Http\Structures\Method\LoanScheduleCalculateArgumentStructure;
use App\Http\Structures\Segment;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LoanScheduleCalculationServiceTest extends TestCase
{
    #[DataProvider('calculateLoanScheduleProvider')]
    public function test_calculateLoanSchedule_assertsEqual(int $interestRate, int $euriborRate, int $amount, int $term, array $euriborAdjustments, array $segmentData): void
    {
        $calculateFunctionArguments = new LoanScheduleCalculateArgumentStructure();
        $calculateFunctionArguments->setBaseInterestInPercent(Mathematical::convertPointsToPercent($interestRate));
        $calculateFunctionArguments->setEuriborInterestInPercent(Mathematical::convertPointsToPercent($euriborRate));
        $calculateFunctionArguments->setLoanAmountInCents($amount);
        $calculateFunctionArguments->setNumberOfPaymentsInMonths($term);

        if (!empty($euriborAdjustments)) {
            $calculateFunctionArguments->setEuriborAdjustments($euriborAdjustments);
        }

        $expected = array_map(static function (array $segmentData): Segment {
            $segment = new Segment();
            $segment->segmentNumber = $segmentData['segmentNumber'];
            $segment->principalPaymentInCents = $segmentData['principalPaymentInCents'];
            $segment->interestPaymentInCents = $segmentData['interestPaymentInCents'];
            $segment->euriborPaymentInCents = $segmentData['euriborPaymentInCents'];
            $segment->totalPaymentInCents = $segmentData['totalPaymentInCents'];
            $segment->remainingPrincipalInCents = $segmentData['remainingPrincipalInCents'];
            return $segment;
        }, $segmentData);

        $calculationService = new CalculationService();
        $actual = $calculationService->calculateLoanScheduleSegments($calculateFunctionArguments);

        $this->assertEquals($expected, $actual);
    }

    public static function calculateLoanScheduleProvider(): array
    {
        return [
            [
                'interestRate' => 534,
                'euriborRate' => 254,
                'amount' => 50000,
                'term' => 16,
                'euriborAdjustments' => [],
                'expected' => [
                    [
                        "segmentNumber" => 1,
                        "principalPaymentInCents" => 2973,
                        "interestPaymentInCents" => 222,
                        "euriborPaymentInCents" => 105,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 47026
                    ],
                    [
                        "segmentNumber" => 2,
                        "principalPaymentInCents" => 2993,
                        "interestPaymentInCents" => 209,
                        "euriborPaymentInCents" => 99,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 44032
                    ],
                    [
                        "segmentNumber" => 3,
                        "principalPaymentInCents" => 3013,
                        "interestPaymentInCents" => 195,
                        "euriborPaymentInCents" => 93,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 41019
                    ],
                    [
                        "segmentNumber" => 4,
                        "principalPaymentInCents" => 3032,
                        "interestPaymentInCents" => 182,
                        "euriborPaymentInCents" => 86,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 37986
                    ],
                    [
                        "segmentNumber" => 5,
                        "principalPaymentInCents" => 3052,
                        "interestPaymentInCents" => 169,
                        "euriborPaymentInCents" => 80,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 34933
                    ],
                    [
                        "segmentNumber" => 6,
                        "principalPaymentInCents" => 3072,
                        "interestPaymentInCents" => 155,
                        "euriborPaymentInCents" => 73,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 31860
                    ],
                    [
                        "segmentNumber" => 7,
                        "principalPaymentInCents" => 3093,
                        "interestPaymentInCents" => 141,
                        "euriborPaymentInCents" => 67,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 28767
                    ],
                    [
                        "segmentNumber" => 8,
                        "principalPaymentInCents" => 3113,
                        "interestPaymentInCents" => 128,
                        "euriborPaymentInCents" => 60,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 25654
                    ],
                    [
                        "segmentNumber" => 9,
                        "principalPaymentInCents" => 3133,
                        "interestPaymentInCents" => 114,
                        "euriborPaymentInCents" => 54,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 22520
                    ],
                    [
                        "segmentNumber" => 10,
                        "principalPaymentInCents" => 3154,
                        "interestPaymentInCents" => 100,
                        "euriborPaymentInCents" => 47,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 19366
                    ],
                    [
                        "segmentNumber" => 11,
                        "principalPaymentInCents" => 3175,
                        "interestPaymentInCents" => 86,
                        "euriborPaymentInCents" => 40,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 16191
                    ],
                    [
                        "segmentNumber" => 12,
                        "principalPaymentInCents" => 3195,
                        "interestPaymentInCents" => 72,
                        "euriborPaymentInCents" => 34,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 12995
                    ],
                    [
                        "segmentNumber" => 13,
                        "principalPaymentInCents" => 3216,
                        "interestPaymentInCents" => 57,
                        "euriborPaymentInCents" => 27,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 9778
                    ],
                    [
                        "segmentNumber" => 14,
                        "principalPaymentInCents" => 3238,
                        "interestPaymentInCents" => 43,
                        "euriborPaymentInCents" => 20,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 6540
                    ],
                    [
                        "segmentNumber" => 15,
                        "principalPaymentInCents" => 3259,
                        "interestPaymentInCents" => 29,
                        "euriborPaymentInCents" => 13,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 3280
                    ],
                    [
                        "segmentNumber" => 16,
                        "principalPaymentInCents" => 3280,
                        "interestPaymentInCents" => 14,
                        "euriborPaymentInCents" => 6,
                        "totalPaymentInCents" => 3302,
                        "remainingPrincipalInCents" => 0
                    ]
                ],
            ],
            [
                'interestRate' => 400,
                'euriborRate' => 394,
                'amount' => 1000000,
                'term' => 12,
                'euriborAdjustments' => [
                    4 => 410,
                    10 => 450,
                ],
                'expected' => [
                    [
                        "segmentNumber" => 1,
                        "principalPaymentInCents" => 80344,
                        "interestPaymentInCents" => 3333,
                        "euriborPaymentInCents" => 3283,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 919655
                    ],
                    [
                        "segmentNumber" => 2,
                        "principalPaymentInCents" => 80875,
                        "interestPaymentInCents" => 3065,
                        "euriborPaymentInCents" => 3019,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 838780
                    ],
                    [
                        "segmentNumber" => 3,
                        "principalPaymentInCents" => 81410,
                        "interestPaymentInCents" => 2795,
                        "euriborPaymentInCents" => 2753,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 757369
                    ],
                    [
                        "segmentNumber" => 4,
                        "principalPaymentInCents" => 81949,
                        "interestPaymentInCents" => 2524,
                        "euriborPaymentInCents" => 2486,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 675420
                    ],
                    [
                        "segmentNumber" => 5,
                        "principalPaymentInCents" => 82491,
                        "interestPaymentInCents" => 2251,
                        "euriborPaymentInCents" => 2307,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 592928
                    ],
                    [
                        "segmentNumber" => 6,
                        "principalPaymentInCents" => 83037,
                        "interestPaymentInCents" => 1976,
                        "euriborPaymentInCents" => 2025,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 509891
                    ],
                    [
                        "segmentNumber" => 7,
                        "principalPaymentInCents" => 83586,
                        "interestPaymentInCents" => 1699,
                        "euriborPaymentInCents" => 1742,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 426304
                    ],
                    [
                        "segmentNumber" => 8,
                        "principalPaymentInCents" => 84139,
                        "interestPaymentInCents" => 1421,
                        "euriborPaymentInCents" => 1456,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 342164
                    ],
                    [
                        "segmentNumber" => 9,
                        "principalPaymentInCents" => 84696,
                        "interestPaymentInCents" => 1140,
                        "euriborPaymentInCents" => 1169,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 257467
                    ],
                    [
                        "segmentNumber" => 10,
                        "principalPaymentInCents" => 85257,
                        "interestPaymentInCents" => 858,
                        "euriborPaymentInCents" => 879,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 172210
                    ],
                    [
                        "segmentNumber" => 11,
                        "principalPaymentInCents" => 85821,
                        "interestPaymentInCents" => 574,
                        "euriborPaymentInCents" => 645,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 86389
                    ],
                    [
                        "segmentNumber" => 12,
                        "principalPaymentInCents" => 86389,
                        "interestPaymentInCents" => 287,
                        "euriborPaymentInCents" => 323,
                        "totalPaymentInCents" => 86960,
                        "remainingPrincipalInCents" => 0
                    ]
                ]

            ],
        ];
    }
}
