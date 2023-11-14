<?php

declare(strict_types=1);

namespace App\Http\Services\LoanSchedule;

use App\Rules\Loan\UserCanEditLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class Validation
{
    public function validateLoanCalculationParameters(Request $request): MessageBag
    {
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'gt:0'],
            'term' => ['required', 'numeric', 'gt:0', 'lt:500'],
            'interestRate' => ['required', 'numeric', 'gt:0'],
            'euriborRate' => ['required', 'numeric'],
        ]);

        return $validator->errors();
    }

    public function validateAdjustEuriborParameters(Request $request): MessageBag
    {
        $validator = Validator::make($request->all(), [
            'loanId' => ['required', 'numeric', 'gt:0', new UserCanEditLoan],
            'euriborAdjustments' => ['required', 'array', 'distinct'],
        ]);

        return $validator->errors();
    }
}
