<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use BelongsToBusiness;

    protected $fillable = ['business_id', 'name', 'description'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
