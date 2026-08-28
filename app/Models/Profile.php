<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{

    use HasFactory;
    protected $fillable = [
        'full_name',
        'headline',
        'location',
        'introduction',
        'about',
        'public_email',
        'github_url',
        'linkedin_url',
        'avatar_path',
        'resume_path',
        'is_available',
        'is_published',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_available' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(
            Media::class,
            'mediable'
        )->ordered();
    }

    public function avatarMedia(): MorphOne
    {
        return $this->morphOne(
            Media::class,
            'mediable'
        )->where('is_primary', true);
    }
}
