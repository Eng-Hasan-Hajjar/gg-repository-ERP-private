<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class FinancialAccount extends Model
{
    use Auditable;
    protected $fillable = [
        'name',
        'phone',
        'branch_id',
        'accountable_type',
        'accountable_id',
    ];

    public function accountable()
    {
        return $this->morphTo();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transactions()
    {
        return $this->hasMany(CashboxTransaction::class);
    }

    public function getBalanceAttribute()
    {
        $in  = $this->transactions()->where('type','in')->sum('amount');
        $out = $this->transactions()->where('type','out')->sum('amount');

        return $in - $out;
    }
}