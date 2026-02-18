<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Diploma extends Model
{
  use Auditable;
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




    public function exams()
    {
        return $this->hasMany(\App\Models\Exam::class);
    }

  
}
