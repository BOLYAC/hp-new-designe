<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable =
        [
            'name',
            'external_id',
            'description',
        ];

    protected $hidden = ['pivot'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
