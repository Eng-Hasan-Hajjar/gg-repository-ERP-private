<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
      protected $fillable = [
        'user_id',
        'login_at',
        'last_activity',
        'logout_at',
        'online_minutes',
        'ip',
        'user_agent',
        'work_date',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'last_activity' => 'datetime',
        'logout_at' => 'datetime',
        'work_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
