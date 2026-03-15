<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Exam extends Model
{
    use Auditable;
    protected $fillable = [
        'title',
        'code',
        'exam_date',
        'type',
        'max_score',
        'pass_score',
        'diploma_id',
        'branch_id',
        'trainer_id',
        'notes'
    ];

    protected $casts = [
        'exam_date' => 'date',
        'max_score' => 'decimal:2',
        'pass_score' => 'decimal:2',
    ];

    public function diploma(): BelongsTo
    {
        return $this->belongsTo(Diploma::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'trainer_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }







    protected static function booted()
    {
        static::addGlobalScope('branch', function ($query) {

            if (!auth()->check()) {
                return;
            }

            $user = auth()->user();

            // السوبر أدمن يرى كل الامتحانات
            if ($user->hasRole('super_admin')) {
                return;
            }

            $branchId = $user->employee?->branch_id;

            if ($branchId) {
                $query->where('branch_id', $branchId);
            }

        });
    }


}
