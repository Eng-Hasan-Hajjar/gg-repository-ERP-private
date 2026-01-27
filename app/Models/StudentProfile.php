<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    protected $fillable = [
        'student_id',
        'arabic_full_name',
        'national_id',
        'birth_date',
        'nationality',
        'address',
        'location',
        'stage',
        'work',
        'education_level',
        'exam_score',
        'notes',
        'photo_path',
        'info_file_path',
        'identity_file_path',
        'has_attendance_certificate',
        'has_certificate_pdf',
        'certificate_pdf_path',
        'has_certificate_card',
        'message_to_student',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'exam_score' => 'decimal:2',
        'has_attendance_certificate' => 'boolean',
        'has_certificate_pdf' => 'boolean',
        'has_certificate_card' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
