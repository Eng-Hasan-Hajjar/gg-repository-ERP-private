<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;
class ExamComponentResult extends Model
{
    use Auditable;
        protected $fillable = [
        'exam_component_id','student_id','score','notes','entered_by'
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function component(): BelongsTo
    {
        return $this->belongsTo(ExamComponent::class, 'exam_component_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
