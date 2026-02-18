<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;
class Branch extends Model
{
    use Auditable;
        protected $fillable = ['name','code'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }


    public function exams()
    {
        return $this->hasMany(\App\Models\Exam::class);
    }



}
