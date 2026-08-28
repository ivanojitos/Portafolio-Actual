<?php

namespace App\Models;

use App\Enums\SkillCategory;
use App\Enums\SkillLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'level',
        'summary',
        'years_experience',
        'is_featured',
        'is_published',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'category' => SkillCategory::class,
            'level' => SkillLevel::class,
            'years_experience' => 'integer',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'position' => 'integer',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('category')
            ->orderBy('position')
            ->orderBy('name');
    }
}
