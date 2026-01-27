<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePayout extends Model
{
    protected $fillable = [
        'employee_id','payout_date','amount','currency',
        'status','reference','notes',
    ];

    protected $casts = [
        'payout_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
