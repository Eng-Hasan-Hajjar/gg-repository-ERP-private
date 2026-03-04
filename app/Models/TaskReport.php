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
}
