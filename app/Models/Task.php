<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use Auditable;
    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'branch_id',
        'created_by',
        'priority',
        'status',
        'due_date',
        'completed_at'
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }



    public function reports()
    {
        return $this->hasMany(TaskReport::class);
    }



    protected static function booted()
    {
        static::addGlobalScope('branch', function ($query) {

            if (!auth()->check()) {
                return;
            }

            $user = auth()->user();

            // السوبر أدمن يرى كل العملاء
            if ($user->hasRole('super_admin')) {
                return;
            }

            $employee = \App\Models\Employee::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->first();

            if (!$employee) {
                return;
            }

            $branchIds = collect([
                $employee->branch_id,
                $employee->secondary_branch_id
            ])
                ->filter()
                ->unique()
                ->values()
                ->all();

            if (!empty($branchIds)) {
                $query->whereIn('branch_id', $branchIds);
            }

        });
    }

    /*
        protected static function booted()
        {
            static::addGlobalScope('branch', function ($query) {

                if (!auth()->check()) {
                    return;
                }

                $user = auth()->user();

                if ($user->hasRole('super_admin')) {
                    return;
                }

                $branchId = $user->employee?->branch_id ?? null;

                if ($branchId) {
                    $query->where('branch_id', $branchId);
                }

            });
        }
    */
}
