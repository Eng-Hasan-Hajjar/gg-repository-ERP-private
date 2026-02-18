<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Role extends Model
{
    use Auditable;
    protected $fillable = [
        'name',
        'label',
        'description',
    ];

 
public function permissions()
{
    return $this->belongsToMany(
        Permission::class,
        'role_permission' // اسم الجدول الصحيح
    );
}

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
