<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Auditable;
use App\Models\Role;

class User extends Authenticatable implements MustVerifyEmail
{
    use Auditable;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_otp_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_otp_expires_at' => 'datetime',
            'email_otp_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }



public function roles()
{
    return $this->belongsToMany(Role::class);
}


public function hasRole($role)
{
    return $this->roles()->where('name', $role)->exists();
}

public function hasPermission($permission)
{
    return $this->roles()
        ->whereHas('permissions', function ($q) use ($permission) {
            $q->where('name', $permission);
        })->exists();
}



/*

public function isOnline()
{
    $session = $this->todaySession;

    if (!$session || !$session->last_activity) {
        return false;
    }

    return now()->diffInMinutes($session->last_activity) <= 2;
}
*/

public function isOnline(): bool
{
    $session = $this->todaySessions()
        ->whereNull('logout_at') // ← لازم تكون الجلسة مفتوحة
        ->latest('login_at')
        ->first();

    if (!$session) {
        return false;
    }

    if (!$session->last_activity) {
        return false;
    }

    // مهلة inactivity (نفس قيمة AutoLogout)
    $timeout = 60; // ثانية

    return now()->diffInSeconds($session->last_activity) <= $timeout;
}

public function getLastSeenAttribute(): ?string
{
    $session = $this->sessions()
        ->latest('last_activity')
        ->first();

    if (!$session || !$session->last_activity) {
        return null;
    }

    if ($this->isOnline()) {
        return 'متصل الآن';
    }

    return $session->last_activity->diffForHumans(); 
}






public function workedSecondsOn($date): int
{
    return $this->sessions()
        ->whereDate('work_date', $date)
        ->get()
        ->sum(function ($session) {

            if (!$session->login_at) {
                return 0;
            }

            $end = $session->logout_at ?? now();

            if ($end->lessThan($session->login_at)) {
                return 0;
            }

            return $session->login_at->diffInSeconds($end);
        });
}





public function sessions()
{
    return $this->hasMany(UserSession::class);
}

public function todaySession()
{
    return $this->hasOne(UserSession::class)
        ->where('work_date', now()->toDateString());
}

/*
public function todaySession()
{
    return $this->hasOne(UserSession::class)->whereDate('work_date', today());
}
*/
/*
public function getTodaySecondsAttribute()
{
    return $this->todaySession?->online_seconds ?? 0;
}

public function getTodayMinutesAttribute()
{
    return round($this->today_seconds / 60, 2);
}

public function getTodayHoursAttribute()
{
    return round($this->today_seconds / 3600, 2);
}

*/




public function todaySessions()
{
    return $this->hasMany(UserSession::class)
        ->whereDate('work_date', today());
}

/*

public function getTodayWorkedSecondsAttribute(): int
{
    return $this->todaySessions
        ->map(function ($session) {

            if (!$session->login_at) {
                return 0;
            }

            $end = $session->logout_at ?? now();

            return $session->login_at->diffInSeconds($end);
        })
        ->sum(); // ← هذا هو السطر الناقص
}
*/
public function getTodayWorkedSecondsAttribute(): int
{
    $sessions = $this->todaySessions()->get(); // ← نجبرها تكون Collection

    return $sessions->sum(function ($session) {

        if (!$session->login_at) {
            return 0;
        }

        $end = $session->logout_at ?? now();

        // حماية من السالب
        if ($end->lessThan($session->login_at)) {
            return 0;
        }

        return $session->login_at->diffInSeconds($end);
    });
}




public function getTodayWorkedFormattedAttribute(): string
{
    $seconds = $this->today_worked_seconds;

    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);

    return "{$hours} ساعة {$minutes} دقيقة";
}


}
