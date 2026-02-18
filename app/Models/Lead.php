<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Lead extends Model
{
  use Auditable;
 protected $fillable = [
    'full_name','phone','whatsapp',
    'first_contact_date','residence','age','organization','email','job',
    'source','need','stage','registration_status',
    'registered_at','notes','branch_id','created_by','student_id','country','province','study',
  ];

  protected $casts = [
    'first_contact_date' => 'date',
    'registered_at' => 'date',
  ];

  public function branch() {
    return $this->belongsTo(Branch::class);
  }

  public function diplomas() {
    return $this->belongsToMany(Diploma::class, 'diploma_lead')
      ->withPivot(['is_primary'])
      ->withTimestamps();
  }

  public function followups() {
    return $this->hasMany(LeadFollowup::class);
  }
public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

  public function student() {
    return $this->belongsTo(Student::class);
  }
  // Helpers
  public function getIsPendingAttribute(): bool
  {
    return $this->registration_status === 'pending';
  }
}
