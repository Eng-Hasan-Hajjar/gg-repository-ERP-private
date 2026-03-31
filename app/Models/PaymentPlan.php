<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{

    protected $fillable = [
        'student_id',
        'diploma_id',
        'total_amount',
        'payment_type',
        'installments_count',
'currency'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function diploma()
    {
        return $this->belongsTo(Diploma::class);
    }

    public function installments()
    {
        return $this->hasMany(PaymentInstallment::class,'plan_id');
    }

}