<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experience extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company',
        'job_title',
        'employment_type',
        'location',
        'company_url',
        'summary',
        'achievements',
        'started_at',
        'ended_at',
        'is_current',
        'is_published',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'achievements' => 'array',
            'started_at' => 'date',
            'ended_at' => 'date',
            'is_current' => 'boolean',
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
            ->orderBy('position')
            ->orderByDesc('started_at');
    }
}
