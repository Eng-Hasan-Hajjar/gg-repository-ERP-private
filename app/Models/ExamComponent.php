<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamComponent extends Model
{
    protected $fillable = [
        'exam_id','title','key','max_score','weight','is_required','sort_order'
    ];

    protected $casts = [
        'max_score' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_required' => 'boolean',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ExamComponentResult::class, 'exam_component_id');
    }
}
