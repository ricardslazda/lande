<?php

namespace App\Rules\Loan;

use App\Models\Loan\Schedule;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class UserCanEditLoan implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var Schedule $loanSchedule */
        $loanSchedule = Schedule::query()->find($value);
        if ($loanSchedule->user_id !== Auth::id()) {
            $fail('Unauthorized action.');
        }
    }
}
