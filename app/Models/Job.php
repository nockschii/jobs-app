<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'company_id',
        'is_active',
        'department',
        'location',
        'city',
        'country',
        'application_email',
        'application_url',
        'hours_per_week',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
