<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadFollowup extends Model
{
    protected $fillable = ['lead_id','followup_date','result','notes','created_by'];
  protected $casts = ['followup_date' => 'date'];

  public function lead() { return $this->belongsTo(Lead::class); }
}
