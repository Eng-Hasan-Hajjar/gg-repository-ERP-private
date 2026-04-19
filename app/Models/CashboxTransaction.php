<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class CashboxTransaction extends Model
{
    use Auditable;

    protected $fillable = [
        'cashbox_id',
        'trx_date',
        'type',
        'amount',
        'currency',
        'category',
        'sub_category',
        'foreign_currency',
        'foreign_amount',
        'reference',
        'notes',
        'status',
        'posted_at',
        'attachment_path',
        'financial_account_id',
        'diploma_id',
        'to_cashbox_id',
        
    ];

    protected $casts = [
        'trx_date' => 'date',
        'amount' => 'decimal:2',
        'foreign_amount' => 'decimal:2',
        'posted_at' => 'datetime',
    ];

    // ── التصنيفات الرئيسية الثابتة ──
    public static array $CATEGORIES = [
        'مصروف' => 'مصروف',
        'بخصوص الدبلومة' => 'بخصوص الدبلومة',
        'تأسيس' => 'تأسيس',
        'أجور مدربين' => 'أجور مدربين',
        'رواتب موظفين' => 'رواتب موظفين',
        'إيراد خارجي' => 'إيراد خارجي',
    ];

    // ── العلاقات ──
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
        return $this->belongsTo(Diploma::class);
    }

    public function financialAccount()
    {
        return $this->belongsTo(FinancialAccount::class);
    }

    public function student()
    {
        return $this->account?->accountable_type === Student::class
            ? $this->account->accountable
            : null;
    }

    // ── Accessors ──
    public function getDisplayTypeAttribute(): string
    {
        return match ($this->type) {
            'transfer' => 'مناقلة',
            'exchange' => 'تصريف',
            'in' => 'مقبوض',
            'out' => 'مدفوع',
            default => $this->type,
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'transfer' => 'warning',
            'exchange' => 'info',
            'in' => 'success',
            'out' => 'danger',
            default => 'secondary',
        };
    }


}