<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class AssetCategory extends Model

{
    use Auditable;
    protected $fillable = ['name','code'];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
