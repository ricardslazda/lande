<?php

declare(strict_types=1);

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $loan_schedule_id
 * @property int $index_number
 * @property int $principal_payment_in_cents
 * @property int $interest_payment_in_cents
 * @property int $euribor_payment_in_cents
 * @property int $total_payment_in_cents
 * @property int $remaining_principal_in_cents
 */
class Segment extends Model
{
    use HasFactory;

    protected $table = 'loan_segments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loan_schedule_id',
        'index_number',
        'principal_payment_in_cents',
        'interest_payment_in_cents',
        'euribor_payment_in_cents',
        'total_payment_in_cents',
        'remaining_principal_in_cents',
    ];

    public static function deleteByScheduleId(int $loanScheduleId)
    {
        self::query()->where('loan_schedule_id', $loanScheduleId)->delete();
    }
}
