<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    protected $fillable = [
        'lead_id',        // ← صاحب الخطة إذا كان عميلاً
        'student_id',     // ← صاحب الخطة إذا كان طالباً
        'diploma_id',
        'total_amount',
        'payment_type',
        'installments_count',
        'currency',
    ];

    // ══ العلاقات ══

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

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
        return $this->hasMany(PaymentInstallment::class, 'plan_id');
    }

    // ══ Accessor: صاحب الخطة (Lead أو Student) ══
    public function getOwnerAttribute()
    {
        return $this->lead_id ? $this->lead : $this->student;
    }

    // ══ Accessor: عدد الدفعات المسجلة ══
    public function getPaymentsCountAttribute(): int
    {
        $account = $this->lead_id
            ? optional($this->lead)->financialAccount
            : optional($this->student)->financialAccount;

        if (!$account) return 0;

        return $account->transactions()
            ->where('diploma_id', $this->diploma_id)
            ->where('type', 'in')
            ->where('status', 'posted')
            ->count();
    }
}