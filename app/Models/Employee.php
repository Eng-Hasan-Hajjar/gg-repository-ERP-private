<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'code','full_name','type','phone','email',
        'branch_id','job_title','status','notes',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function diplomas(): BelongsToMany
    {
        return $this->belongsToMany(Diploma::class, 'diploma_employee')->withTimestamps();
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(EmployeeContract::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(EmployeePayout::class);
    }
}
