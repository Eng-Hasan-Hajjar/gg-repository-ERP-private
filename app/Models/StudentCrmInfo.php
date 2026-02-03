<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCrmInfo extends Model
{ 
    
protected $fillable = [
    'student_id','first_contact_date','residence','age','organization',
    'source','need','stage','converted_at','notes'
  ];

  protected $casts = [
    'first_contact_date' => 'date',
    'converted_at' => 'datetime',
  ];

  public function student() { return $this->belongsTo(Student::class); }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
