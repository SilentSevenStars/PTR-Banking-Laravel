<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'purpose',
        'term',
        'interest_rate',
        'status',
        'monthly_payment',
        'remaining_balance',
        'months_paid',
        'next_due_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
