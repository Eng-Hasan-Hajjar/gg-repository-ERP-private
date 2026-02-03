<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
  protected $fillable = [
    'student_id','arabic_full_name','nationality','birth_date','national_id',
    'address','photo_path','info_file_path','identity_file_path',
    'certificate_pdf_path','exam_score','notes'
  ];

  protected $casts = ['birth_date' => 'date'];

  public function student() { return $this->belongsTo(Student::class); }
}
