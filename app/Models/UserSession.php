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
        'session_id',
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



    public function getDurationInSecondsAttribute()
{
    if (!$this->login_at) {
        return 0;
    }

    $end = $this->logout_at ?? now();

    return $this->login_at->diffInSeconds($end);
}

public function getDurationFormattedAttribute()
{
    $seconds = $this->duration_in_seconds;

    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);

    return "{$hours} ساعة {$minutes} دقيقة";
}


protected static function booted()
{
    static::creating(function ($session) {

        if (!$session->work_date) {
            $session->work_date = now()->toDateString();
        }

    });
}


}
