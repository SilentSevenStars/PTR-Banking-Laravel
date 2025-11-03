<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'civil_status',
        'nationality',
        'present_address',
        'permanent_address',
        'contact_number',
        'email',
        'employment_status',
        'company_name',
        'company_address',
        'company_phone',
        'position',
        'monthly_income',
        'years_employed',
        'valid_id_type',
        'valid_id_number',
        'valid_id_front_path',
        'valid_id_back_path',
        'proof_of_income_path',
        'proof_of_billing_path',
        'status',
        'admin_notes',
        'reviewed_at',
        'reviewed_by'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'monthly_income' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
