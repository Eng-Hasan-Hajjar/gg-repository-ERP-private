<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class CashboxTransaction extends Model
{
    use Auditable;
    protected $fillable = [
        'cashbox_id','trx_date','type','amount','currency',
        'category','reference','notes','status','posted_at','attachment_path','financial_account_id','diploma_id',
    ];

    protected $casts = [
        'trx_date' => 'date',
        'amount' => 'decimal:2',
        'posted_at' => 'datetime',
    ];

    public function cashbox(): BelongsTo
    {
        return $this->belongsTo(Cashbox::class);
    }



    public function account()
    {
        return $this->belongsTo(FinancialAccount::class, 'financial_account_id');
    }
    


    public function diploma()
    {
        return $this->belongsTo(\App\Models\Diploma::class);
    }



    public function student()
    {
        return $this->account?->accountable_type === \App\Models\Student::class
            ? $this->account->accountable
            : null;
    }




public function financialAccount()
{
    return $this->belongsTo(\App\Models\FinancialAccount::class);
}



    /**
 * نوع العرض النهائي للنوع (مع التعامل مع المناقلة)
 */
public function getDisplayTypeAttribute(): string
{
    if ($this->type === 'transfer') {
        return 'مناقلة';
    }

    return $this->type === 'in' ? 'مقبوض' : 'مدفوع';
}

/**
 * لون البادج المناسب لكل نوع
 */
public function getTypeBadgeClassAttribute(): string
{
    if ($this->type === 'transfer') {
        return 'warning'; // أصفر
    }

    return $this->type === 'in' ? 'success' : 'danger';
}



}
