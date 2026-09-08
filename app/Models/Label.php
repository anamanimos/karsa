<?php

namespace App\Models;

use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Label extends Model
{
    use BelongsToBusiness;

    protected $fillable = ['business_id', 'name'];

    public function galleries(): BelongsToMany
    {
        return $this->belongsToMany(Gallery::class);
    }
}
