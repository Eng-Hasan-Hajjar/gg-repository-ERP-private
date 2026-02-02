<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
        protected $fillable = [
        'university_id',
        'first_name','last_name','full_name',
        'phone','whatsapp',
        'branch_id','mode',
        'status',
        'is_confirmed','confirmed_at',


        'diploma_name','diploma_code','level',
        'email',
    
        'registration_status',
        
        // ✅ هذا هو المهم
        'diploma_id',

    ];

    protected $casts = [
        'is_confirmed' => 'boolean',
        'confirmed_at' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }


        public function profile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
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

public function diplomas()
{
  return $this->belongsToMany(\App\Models\Diploma::class, 'diploma_student')
      ->withPivot(['is_primary','enrolled_at','status','notes'])
      ->withTimestamps();
}



}
