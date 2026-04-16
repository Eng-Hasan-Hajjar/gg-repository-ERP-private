<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;
class Asset extends Model
{
    use Auditable;
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










    protected static function booted()
{
    static::addGlobalScope('branch', function ($query) {

        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        if ($user->hasRole('super_admin')  || $user->hasPermission('manage_assets')) {
            return;
        }

        $employee = \App\Models\Employee::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->first();

        if ($employee && $employee->branch_id) {
            $query->where('branch_id', $employee->branch_id);
        }

    });
}



}
