<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Diploma extends Model
{
  use Auditable;
  protected $fillable = ['name', 'field', 'code', 'type', 'is_active', 'details_pdf'];

  public function students()
  {
    return $this->belongsToMany(Student::class, 'diploma_student')->withTimestamps();
  }

  public function leads()
  {
    return $this->belongsToMany(Lead::class, 'diploma_lead')->withTimestamps();
  }
  protected $casts = [
    'is_active' => 'boolean',
  ];




  public function exams()
  {
    return $this->hasMany(\App\Models\Exam::class);
  }




  public function getTypeLabelAttribute()
  {
    return match ($this->type) {
      'online' => 'أونلاين',
      'onsite' => 'حضوري',
      default => '-'
    };
  }




      // ← رابط الـ PDF جاهز للاستخدام في الـ view
    public function getPdfUrlAttribute(): ?string
    {
        return $this->details_pdf
            ? asset('storage/' . $this->details_pdf)
            : null;
    }

    


}
