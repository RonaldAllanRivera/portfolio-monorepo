<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'headline',
        'about_me',
        'logo',
        'favicon',
        'profile_picture',
        'seo_title',
        'seo_description',
        'seo_keywords',
        // Contact
        'contact_email',
        'contact_phone',
        'contact_whatsapp',
        // Socials
        'github_url',
        'linkedin_url',
        'twitter_url',
        'youtube_url',
        'dribbble_url',
        'behance_url',
        'date_of_birth',
        'gender',
        'marital_status',
        'nationality',
        // Address
        'address_line1',
        'address_line2',
        'address_city',
        'address_state',
        'address_postal_code',
        'address_country',
        // Availability
        'open_to_work',
        'hourly_rate',
        'preferred_roles',
        'sort_order',
    ];

    protected $casts = [
        'seo_keywords' => 'array',
        'date_of_birth' => 'date',
        'open_to_work' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'preferred_roles' => 'array',
        'sort_order' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('created_at');
    }
}
