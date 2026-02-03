<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diploma extends Model
{
      protected $fillable = ['name','code','is_active'];

  public function students() {
    return $this->belongsToMany(Student::class, 'diploma_student')->withTimestamps();
  }

  public function leads() {
    return $this->belongsToMany(Lead::class, 'diploma_lead')->withTimestamps();
  }
       protected $casts = [
        'is_active' => 'boolean',
    ];




    public function exams(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Exam::class);
    }

  
}
