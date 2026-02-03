<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
  protected $fillable = [
    'student_id',

    // شخصي
    'arabic_full_name','nationality','birth_date','national_id','address',

    // دراسة/عمل
    'level','stage_in_state','job','education_level',

    // ملفات ووثائق
    'photo_path','info_file_path','identity_file_path',
    'certificate_pdf_path','attendance_certificate_path','certificate_card_path',

    // تقييم وملاحظات
    'exam_score','notes','message_to_send',
  ];

  protected $casts = [
    'birth_date' => 'date',
    'exam_score' => 'decimal:2',
  ];

  public function student()
  {
    return $this->belongsTo(Student::class);
  }

  // ✅ بدل ما نخزن "موجود أو لا" نخليه محسوب تلقائياً:
  public function getHasPhotoAttribute(): bool
  {
    return !empty($this->photo_path);
  }

  public function getHasInfoFileAttribute(): bool
  {
    return !empty($this->info_file_path);
  }

  public function getHasIdentityFileAttribute(): bool
  {
    return !empty($this->identity_file_path);
  }

  public function getHasAttendanceCertificateAttribute(): bool
  {
    return !empty($this->attendance_certificate_path);
  }

  public function getHasCertificatePdfAttribute(): bool
  {
    return !empty($this->certificate_pdf_path);
  }

  public function getHasCertificateCardAttribute(): bool
  {
    return !empty($this->certificate_card_path);
  }
}
