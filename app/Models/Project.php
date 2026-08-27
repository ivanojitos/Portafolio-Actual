<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'challenge',
        'solution',
        'results',
        'repository_url',
        'demo_url',
        'cover_image',
        'is_featured',
        'is_published',
        'position',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'position' => 'integer',
            'published_at' => 'datetime',
        ];
    }
}
