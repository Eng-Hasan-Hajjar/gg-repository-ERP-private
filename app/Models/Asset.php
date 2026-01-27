<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = [
        'asset_tag','name','description',
        'asset_category_id','branch_id',
        'condition','purchase_date','purchase_cost','currency',
        'serial_number','location','photo_path',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function getConditionLabelAttribute(): string
    {
        return match($this->condition){
            'good' => 'جيد',
            'maintenance' => 'صيانة',
            default => 'خارج الخدمة',
        };
    }

    public function getConditionBadgeClassAttribute(): string
    {
        return match($this->condition){
            'good' => 'bg-success',
            'maintenance' => 'bg-warning text-dark',
            default => 'bg-danger',
        };
    }
}
