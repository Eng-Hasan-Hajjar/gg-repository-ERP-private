<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Cashbox extends Model
{
    use Auditable;
    protected $fillable = [
        'name','code','branch_id','currency','status','opening_balance',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CashboxTransaction::class);
    }

    // رصيد محسوب (اختياري للاستخدام في الواجهات)
    public function getCurrentBalanceAttribute(): float
    {
        $in  = (float) $this->transactions()->where('status','posted')->where('type','in')->sum('amount');
        $out = (float) $this->transactions()->where('status','posted')->where('type','out')->sum('amount');
        return (float)$this->opening_balance + $in - $out;
    }
}
