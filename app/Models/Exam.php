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
        'title','code','exam_date','type',
        'max_score','pass_score',
        'diploma_id','branch_id','trainer_id',
        'notes'
    ];

    protected $casts = [
        'exam_date' => 'date',
        'max_score' => 'decimal:2',
        'pass_score'=> 'decimal:2',
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


    public function components()
    {
        return $this->hasMany(\App\Models\ExamComponent::class)->orderBy('sort_order');
    }

    public function registrations()
    {
        return $this->hasMany(\App\Models\ExamRegistration::class);
    }

    public function students()
    {
        return $this->belongsToMany(\App\Models\Student::class, 'exam_registrations')
            ->withPivot(['status','registered_at','notes'])
            ->withTimestamps();
    }



}
