<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = [
        'title', 'description', 'start_date', 'end_date',
        'start_time', 'end_time', 'type', 'color',
        'all_day', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'all_day'    => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // أنواع الأحداث
    public static function types(): array
    {
        return [
            'general'   => ['label' => 'عام',              'color' => '#0ea5e9', 'icon' => 'bi-calendar'],
            'session'   => ['label' => 'جلسة / دبلومة',   'color' => '#10b981', 'icon' => 'bi-mortarboard'],
            'campaign'  => ['label' => 'حملة إعلانية',    'color' => '#f59e0b', 'icon' => 'bi-megaphone'],
            'birthday'  => ['label' => 'عيد ميلاد',       'color' => '#ec4899', 'icon' => 'bi-balloon'],
            'reminder'  => ['label' => 'تذكير',            'color' => '#8b5cf6', 'icon' => 'bi-bell'],
            'other'     => ['label' => 'أخرى',             'color' => '#64748b', 'icon' => 'bi-three-dots'],
        ];
    }
}