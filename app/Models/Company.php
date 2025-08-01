<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'company_id');
    }
}
