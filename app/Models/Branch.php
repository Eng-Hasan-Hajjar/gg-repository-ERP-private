<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
        protected $fillable = ['name','code'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
