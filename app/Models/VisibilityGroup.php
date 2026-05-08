<?php
// app/Models/VisibilityGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisibilityGroup extends Model
{
    protected $fillable = ['name', 'notes'];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'visibility_group_employee')
            ->withPivot('role_in_group')
            ->withTimestamps();
    }

    public function managers()
    {
        return $this->belongsToMany(Employee::class, 'visibility_group_employee')
            ->wherePivot('role_in_group', 'manager')
            ->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany(Employee::class, 'visibility_group_employee')
            ->withPivot('role_in_group')
            ->withTimestamps();
    }
}