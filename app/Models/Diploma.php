<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Diploma extends Model
{
    use Auditable;

    protected $fillable = ['name', 'field', 'code', 'type', 'is_active', 'details_pdf', 'branch_id'];


    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'diploma_student')->withTimestamps();
    }

    public function leads()
    {
        return $this->belongsToMany(Lead::class, 'diploma_lead')->withTimestamps();
    }

    public function exams()
    {
        return $this->hasMany(\App\Models\Exam::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            'online' => 'أونلاين',
            'onsite' => 'حضوري',
            default => '-',
        };
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->details_pdf
            ? asset('storage/' . $this->details_pdf)
            : null;
    }


    protected static function booted()
    {
        static::addGlobalScope('branch', function ($query) {
            if (!auth()->check())
                return;

            $user = auth()->user();

            // ✅ super_admin أو view_all_diplomas → يرى الكل
            if (
                $user->hasRole('super_admin')
                || $user->hasPermission('view_all_diplomas')
            ) {
                return;
            }

            // ✅ يرى دبلومات فرعه فقط
            $employee = \App\Models\Employee::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->whereNotNull('user_id')
                ->first();

            $branchIds = collect([
                $employee?->branch_id,
                $employee?->secondary_branch_id,
            ])->filter()->unique()->values()->all();

            if (!empty($branchIds)) {
                $query->whereIn('branch_id', $branchIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        });
    }

}