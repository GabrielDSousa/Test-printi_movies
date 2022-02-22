<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $validated)
 * @method static latest()
 */
class Movies extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * @param $query
     * @param  array  $filters
     */
    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['title'] ?? false, fn($query, $title) => $query
            ->where('title', 'like', "%{$title}%"));

        $query->when($filters['category'] ?? false, fn($query, $category) => $query
            ->where('category', (string)$category));
    }
}
