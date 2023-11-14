<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\RPC;

use App\Helpers\Mathematical as MathematicalHelper;
use App\Http\Controllers\API\RPC\BaseController as BaseController;
use App\Http\Services\LoanSchedule\Calculation as CalculationService;
use App\Http\Services\LoanSchedule\Validation as ValidationService;
use App\Http\Structures\Method\LoanScheduleCalculateArgumentStructure;
use App\Http\Structures\Segment as SegmentStructure;
use App\Models\Loan\Schedule;
use App\Models\Loan\Segment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LoanScheduleController extends BaseController
{
    private const SHOULD_CACHE_LOAN_SCHEDULE_CALCULATION = false;

    protected ValidationService $validationService;
    protected CalculationService $calculationService;

    public function __construct(ValidationService $validationService, CalculationService $calculationService)
    {
        $this->validationService = $validationService;
        $this->calculationService = $calculationService;
    }

    /**
     * @OA\POST(
     * path="/api/loan-schedule/calculate",
     * summary="Calculate Loan Schedule",
     * tags={"Loans"},
     * @OA\RequestBody(
     *    @OA\JsonContent(
     *       required={"amount","interestRateBasis","euriborRateBasis","term"},
     *       @OA\Property(property="amount", type="integer", format="int64", example="1000000"),
     *       @OA\Property(property="interestRate", type="integer", format="int64", example="400"),
     *       @OA\Property(property="euriborRate", type="integer", format="int64", example="394"),
     *       @OA\Property(property="term", type="integer", format="int64", example="12"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Loan schedule has been calculated successfully.",
     *    @OA\JsonContent(
     *       @OA\Property(property="loanId", type="int64", example="10"),
     *       @OA\Property(property="segments", type="string", example="[{'segmentNumber': 1, 'principalPaymentInCents': 80344, 'interestPaymentInCents': 3333, 'euriborPaymentInCents': 3283, 'totalPaymentInCents': 86960}]")
     *        )
     *     ),
     *   @OA\Response(
     *    response=400,
     *    description="Request body validation error.",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="false"),
     *       @OA\Property(property="message", type="string", example="Request body validation error."),
     *       @OA\Property(property="data", type="string", example="['The amount field must be a number.']"),
     *        )
     *     )
     * )
     */
    public function calculateLoanSchedule(Request $request): JsonResponse
    {
        $errors = $this->validationService->validateLoanCalculationParameters($request);

        if (!$errors->isEmpty()) {
            return $this->sendError('Request body validation error.', $errors, 400);
        }

        $segments = $this->getCachedSegments($request, $this->getCalculateScheduleStructureFromRequest($request));
        $schedule = new Schedule();

        DB::transaction(function () use ($segments, $request, $schedule): void {
            $schedule = $this->buildLoanSchedule($schedule, $request);
            $schedule->save();

            foreach ($segments as $segmentStructure) {
                $segmentModel = new Segment();
                $segmentModel = $this->buildSegment($segmentModel, $schedule->id, $segmentStructure);
                $segmentModel->save();
            }
        });

        return $this->sendResponse([
            'loanId' => $schedule->id,
            'segments' => $segments,
        ], 'Loan schedule has been calculated successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/loan-schedule/adjust-euribor",
     * summary="Adjust Loan Schedule Euribor Rate",
     * tags={"Loans"},
     * @OA\RequestBody(
     *    @OA\JsonContent(
     *       required={"loanId","euriborAdjustments"},
     *       @OA\Property(property="loanId", type="integer", format="int64", example="10"),
     *       @OA\Property(property="euriborAdjustments[12]", type="integer", format="int64", example="357"),
     *       @OA\Property(property="euriborAdjustments[24]", type="integer", format="int64", example="653"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Euribor has been adjusted successfully.",
     *    @OA\JsonContent(
     *       @OA\Property(property="loanId", type="int64", example="10"),
     *       @OA\Property(property="segments", type="string", example="[{'segmentNumber': 1, 'principalPaymentInCents': 80344, 'interestPaymentInCents': 3333, 'euriborPaymentInCents': 3283, 'totalPaymentInCents': 86960}]")
     *        )
     *     ),
     *   @OA\Response(
     *    response=400,
     *    description="Request body validation error.",
     *    @OA\JsonContent(
     *       @OA\Property(property="success", type="string", example="false"),
     *       @OA\Property(property="message", type="string", example="Request body validation error."),
     *       @OA\Property(property="data", type="string", example="['The loanId field must be a number.']"),
     *        )
     *     )
     * )
     */
    public function adjustEuribor(Request $request): JsonResponse
    {
        $errors = $this->validationService->validateAdjustEuriborParameters($request);

        if (!$errors->isEmpty()) {
            return $this->sendError('Request body validation error.', $errors, 400);
        }

        /** @var Schedule $schedule */
        $schedule = Schedule::query()->find((int)$request->get('loanId'));

        $calculateFunctionArguments = $this->getCalculateScheduleStructureFromModel($schedule);
        $calculateFunctionArguments->setEuriborAdjustments($request->get('euriborAdjustments'));

        $segments = $this->getCachedSegments($request, $calculateFunctionArguments);

        DB::transaction(function () use ($segments, $request, $schedule): void {
            Segment::deleteByScheduleId($schedule->id);

            foreach ($segments as $segmentStructure) {
                $segmentModel = new Segment();
                $segmentModel = $this->buildSegment($segmentModel, $schedule->id, $segmentStructure);
                $segmentModel->save();
            }
        });

        return $this->sendResponse([
            'loanId' => $schedule->id,
            'segments' => $segments,
        ], 'Euribor has been adjusted successfully.');
    }

    protected function getCalculateScheduleStructureFromRequest(Request $request): LoanScheduleCalculateArgumentStructure
    {
        $calculateFunctionArguments = new LoanScheduleCalculateArgumentStructure();
        $calculateFunctionArguments->setBaseInterestInPercent(MathematicalHelper::convertPointsToPercent((int)$request->get('interestRate')));
        $calculateFunctionArguments->setEuriborInterestInPercent(MathematicalHelper::convertPointsToPercent((int)$request->get('euriborRate')));
        $calculateFunctionArguments->setLoanAmountInCents((int)$request->get('amount'));
        $calculateFunctionArguments->setNumberOfPaymentsInMonths((int)$request->get('term'));

        if ($request->has('euriborAdjustments')) {
            $calculateFunctionArguments->setEuriborAdjustments($request->get('euriborAdjustments'));
        }

        return $calculateFunctionArguments;
    }

    protected function getCalculateScheduleStructureFromModel(Schedule $loanSchedule): LoanScheduleCalculateArgumentStructure
    {
        $calculateFunctionArguments = new LoanScheduleCalculateArgumentStructure();
        $calculateFunctionArguments->setBaseInterestInPercent(MathematicalHelper::convertPointsToPercent($loanSchedule->initial_interest_rate_in_points));
        $calculateFunctionArguments->setEuriborInterestInPercent(MathematicalHelper::convertPointsToPercent($loanSchedule->initial_euribor_in_points));
        $calculateFunctionArguments->setLoanAmountInCents($loanSchedule->initial_loan_in_cents);
        $calculateFunctionArguments->setNumberOfPaymentsInMonths($loanSchedule->term);

        return $calculateFunctionArguments;
    }

    protected function buildLoanSchedule(Schedule $loanSchedule, Request $request): Schedule
    {
        $loanSchedule->user_id = Auth::id();
        $loanSchedule->initial_loan_in_cents = (int)$request->get('amount');
        $loanSchedule->initial_interest_rate_in_points = (int)$request->get('interestRate');
        $loanSchedule->initial_euribor_in_points = (int)$request->get('euriborRate');
        $loanSchedule->term = (int)$request->get('term');

        return $loanSchedule;
    }

    protected function buildSegment(Segment $segmentModel, int $loanScheduleId, SegmentStructure $segmentStructure): Segment
    {
        $segmentModel->loan_schedule_id = $loanScheduleId;
        $segmentModel->index_number = $segmentStructure->segmentNumber;
        $segmentModel->principal_payment_in_cents = $segmentStructure->principalPaymentInCents;
        $segmentModel->interest_payment_in_cents = $segmentStructure->interestPaymentInCents;
        $segmentModel->euribor_payment_in_cents = $segmentStructure->euriborPaymentInCents;
        $segmentModel->total_payment_in_cents = $segmentStructure->totalPaymentInCents;
        $segmentModel->remaining_principal_in_cents = $segmentStructure->remainingPrincipalInCents;

        return $segmentModel;
    }

    protected function getCachedSegments(Request $request, LoanScheduleCalculateArgumentStructure $calculateFunctionArguments): array
    {
        if (!self::SHOULD_CACHE_LOAN_SCHEDULE_CALCULATION) {
            Cache::clear();

            return $this->calculationService->calculateLoanScheduleSegments($calculateFunctionArguments);
        }

        $requestHash = md5(json_encode($request->toArray()));
        $cacheKey = sprintf('loan_schedule_segments_%s', $requestHash);
        $cachedSegments = Cache::get($cacheKey);

        if ($cachedSegments) {
            $segments = $cachedSegments;
        } else {
            $segments = $this->calculationService->calculateLoanScheduleSegments($calculateFunctionArguments);
            Cache::set($cacheKey, $segments);
        }

        return $segments;
    }
}
