<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
class TaskReport extends Model
{
    protected $fillable = [
        'employee_id',
        'task_id',
        'report_type',
        'report_date',
        'title',
        'notes',
        'file_path'
    ];

    protected $casts = [
        'report_date' => 'date'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }


    
/*

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
*/

}
