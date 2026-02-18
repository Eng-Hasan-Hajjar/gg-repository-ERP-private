<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class LeaveRequest extends Model
{
    use Auditable;
    protected $fillable = [
        'employee_id','type','start_date','end_date','reason',
        'status','approved_by','approved_at','admin_note'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function employee(): BelongsTo { return $this->belongsTo(Employee::class); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class,'approved_by'); }
}
