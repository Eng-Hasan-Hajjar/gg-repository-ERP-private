<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentInstallment extends Model
{

    protected $fillable = [
        'plan_id',
        'amount',
        'due_date'
    ];

    protected $casts = [
        'due_date' => 'date'
    ];

    public function plan()
    {
        return $this->belongsTo(PaymentPlan::class,'plan_id');
    }

}