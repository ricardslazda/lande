<?php

declare(strict_types=1);

namespace App\Models\Loan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int initial_interest_rate_in_points
 * @property int initial_euribor_in_points
 * @property int initial_loan_in_cents
 * @property int $term
 */
class Schedule extends Model
{
    use HasFactory;

    protected $table = 'loan_schedules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'initial_interest_rate_in_points',
        'initial_euribor_in_points',
        'initial_loan_in_cents',
        'term',
    ];
}
