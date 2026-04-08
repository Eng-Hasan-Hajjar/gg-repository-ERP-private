<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Lead extends Model
{
  use Auditable;
  protected $fillable = [
    'full_name',
    'phone',
    'whatsapp',
    'first_contact_date',
    'residence',
    'age',
    'organization',
    'email',
    'job',
    'source',
    'need',
    'stage',
    'registration_status',
    'registered_at',
    'notes',
    'branch_id',
    'created_by',
    'student_id',
    'country',
    'province',
    'study',
  ];

  protected $casts = [
    'first_contact_date' => 'date',
    'registered_at' => 'date',
  ];

  public function branch()
  {
    return $this->belongsTo(Branch::class);
  }

  public function diplomas()
  {
    return $this->belongsToMany(Diploma::class, 'diploma_lead')
      ->withPivot(['is_primary'])
      ->withTimestamps();
  }

  public function followups()
  {
    return $this->hasMany(LeadFollowup::class);
  }
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function student()
  {
    return $this->belongsTo(Student::class);
  }
  // Helpers
  public function getIsPendingAttribute(): bool
  {
    return $this->registration_status === 'pending';
  }





  public function financialAccount()
  {
    return $this->morphOne(FinancialAccount::class, 'accountable');
  }









protected static function booted()
{
    static::addGlobalScope('branch', function ($query) {

        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // السوبر أدمن يرى كل العملاء
        if ($user->hasRole('super_admin')) {
            return;
        }

        $employee = \App\Models\Employee::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->first();

        if (!$employee) {
            return;
        }

        $branchIds = collect([
            $employee->branch_id,
            $employee->secondary_branch_id
        ])
        ->filter()
        ->unique()
        ->values()
        ->all();

        if (!empty($branchIds)) {
            $query->whereIn('branch_id', $branchIds);
        }

    });
}

/*

  protected static function booted()
  {
    static::addGlobalScope('branch', function ($query) {

      if (!auth()->check()) {
        return;
      }

      $user = auth()->user();

      if (!$user->hasRole('super_admin')) {

        $branchId = $user->employee?->branch_id;
        // $branchId2 = $user->employee?->branch_id;
        if ($branchId) {
          $query->where('branch_id', $branchId);
        }

      }

    });




    static::addGlobalScope('branch', function ($query) {
      if (!auth()->check())
        return;

      $user = auth()->user();
      if ($user->hasRole('super_admin'))
        return;

      $employee = \App\Models\Employee::withoutGlobalScopes()
        ->where('user_id', $user->id)
        ->first();

      if ($employee) {
        $branchIds = collect([$employee->branch_id, $employee->secondary_branch_id])
          ->filter()->unique()->all();

        if (count($branchIds)) {
          $query->whereIn('branch_id', $branchIds);
        }
      }
    });



  }

*/

}
