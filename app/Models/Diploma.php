<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Diploma extends Model
{
    protected $fillable = ['name','code','field','is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];




    public function exams(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Exam::class);
    }
public function students()
{
    return $this->belongsToMany(\App\Models\Student::class, 'diploma_student')
        ->withPivot(['is_primary','enrolled_at','status','notes'])
        ->withTimestamps();
}


}
