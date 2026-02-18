<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class StudentCrmInfo extends Model
{ 
  use Auditable;
    
protected $fillable = [
    'student_id','first_contact_date','residence','age','organization','email','job',
    'source','need','stage','converted_at','notes','country','province','study','created_by',
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


    public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}



}
