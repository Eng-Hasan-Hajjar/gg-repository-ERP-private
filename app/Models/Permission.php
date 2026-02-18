<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Permission extends Model
{
    use Auditable;
    protected $fillable = [
        'name',
        'label',
        'module',
    ];


    public function roles()
{
    return $this->belongsToMany(
        Role::class,
        'role_permission'
    );
}


}
