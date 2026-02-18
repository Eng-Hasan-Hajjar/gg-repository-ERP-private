<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class LeadFollowup extends Model
{
  use Auditable;
    protected $fillable = ['lead_id','followup_date','result','notes','created_by'];
  protected $casts = ['followup_date' => 'date'];

  public function lead() { return $this->belongsTo(Lead::class); }
}
