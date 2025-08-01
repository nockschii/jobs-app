<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'description',
        'address',
        'city',
        'country',
        'postal_code',
        'phone',
        'website',
        'linkedin_url',
        'industry',
        'logo',
    ];

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'company_id');
    }
}
