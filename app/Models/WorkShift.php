<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
class WorkShift extends Model
{
    use Auditable;
    protected $fillable = [
        'name','code','start_time','end_time','grace_minutes','is_active','notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
