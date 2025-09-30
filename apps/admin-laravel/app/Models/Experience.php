<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experience extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'employment_type',
        'company_name',
        'is_current',
        'start_date',
        'end_date',
        'location',
        'location_type',
        'description',
        'profile_headline',
        'media',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
        'media' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * Get the user that owns the experience.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get current experiences.
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope to order by sort order and date.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('start_date');
    }

    /**
     * Skills used in this experience.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withTimestamps();
    }
}
