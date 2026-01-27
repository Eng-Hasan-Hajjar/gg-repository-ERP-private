<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
        protected $fillable = [
        'university_id',
        'first_name','last_name','full_name',
        'phone','whatsapp',
        'branch_id','mode',
        'status',
        'is_confirmed','confirmed_at',


        'diploma_name','diploma_code','level',
        'email',
    
        'registration_status',
        


    ];

    protected $casts = [
        'is_confirmed' => 'boolean',
        'confirmed_at' => 'datetime',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }


        public function profile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    // مساعدات عرض
    public function getIsPendingAttribute(): bool
    {
        return $this->registration_status === 'pending';
    }


       public function diploma(): BelongsTo
    {
        return $this->belongsTo(Diploma::class);
    }


    /**
     * *************************/

    public function extra(): HasOne
    {
        return $this->hasOne(StudentExtraField::class);
    }
}
