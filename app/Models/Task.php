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
        'title','description','assigned_to','branch_id','created_by',
        'priority','status','due_date','completed_at'
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function assignee(): BelongsTo { return $this->belongsTo(Employee::class,'assigned_to'); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class,'created_by'); }

    public function comments(): HasMany { return $this->hasMany(TaskComment::class); }
}
