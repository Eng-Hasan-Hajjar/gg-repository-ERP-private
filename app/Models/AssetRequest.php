<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetRequest extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'asset_id',
        'type',
        'priority',
        'title',
        'description',
        'status',
        'manager_notes',
        'reviewed_by',
        'reviewed_at',

        'transferred_to',
        'transferred_by',
        'transferred_at',
        'approved_by',
        'approved_at',

    ];


    protected $casts = [
        'reviewed_at' => 'datetime',
        'transferred_at' => 'datetime', // ✅
        'approved_at' => 'datetime', // ✅
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد المراجعة',
            'approved' => 'مقبول',
            'rejected' => 'مرفوض',
             'transferred' => 'مُرحَّل',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'transferred' => 'primary',
            default => 'secondary',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'purchase' => 'شراء',
            'repair' => 'إصلاح',
            default => $this->type,
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'منخفضة',
            'normal' => 'عادية',
            'urgent' => 'عاجل',
            default => $this->priority,
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'secondary',
            'normal' => 'info',
            'urgent' => 'danger',
            default => 'secondary',
        };
    }

    public function getPriorityIconAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'bi-arrow-down-circle',
            'normal' => 'bi-dash-circle',
            'urgent' => 'bi-exclamation-circle-fill',
            default => 'bi-dash-circle',
        };
    }
}