<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    protected $fillable = [
        'name','code','start_time','end_time','grace_minutes','is_active','notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
