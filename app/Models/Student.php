<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\Auditable;

class Student extends Model
{
  use Auditable;
    protected $fillable = [
    'university_id','first_name','last_name','full_name',
    'phone','whatsapp',
    'branch_id','mode','status',
    'registration_status','is_confirmed','confirmed_at',
  ];

  protected $casts = [
    'is_confirmed' => 'boolean',
    'confirmed_at' => 'datetime',
  ];

  public function branch() {
    return $this->belongsTo(Branch::class);
  }

  public function diplomas() {
    return $this->belongsToMany(Diploma::class, 'diploma_student')
      ->withPivot(['is_primary','enrolled_at','status','notes'    ,  'has_attendance_certificate',
        'attendance_certificate_path',
        'certificate_pdf_path',
        'certificate_card_path',
               'rating',
        
        'ended_at',
        'certificate_delivered'
        ])
      ->withTimestamps();
  }

  public function profile() {
    return $this->hasOne(StudentProfile::class);
  }

  public function crmInfo() {
    return $this->hasOne(StudentCrmInfo::class);
  }
 

    // مساعدات عرض
    public function getIsPendingAttribute(): bool
    {
        return $this->registration_status === 'pending';
    }


  

    /**
     * *************************/

    public function extra(): HasOne
    {
        return $this->hasOne(StudentExtraField::class);
    }
    public function exams()
    {
        return $this->belongsToMany(\App\Models\Exam::class, 'exam_registrations')
            ->withPivot(['status','registered_at','notes'])
            ->withTimestamps();
    }

   public function diploma()
{
  return $this->belongsTo(\App\Models\Diploma::class, 'diploma_id');
}

}
