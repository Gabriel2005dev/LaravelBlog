<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    public const SEARCH_MIN_LENGTH = 2;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_likes')->withTimestamps();
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_posts')->withTimestamps();
    }

     public function scopeForFeed(Builder $query, int $userId): Builder
    {
        return $query
            ->with(['user:id,name,avatar', 'comments.user:id,name,avatar'])
            ->withCount(['comments', 'likedByUsers as likes_count'])
            ->withExists([
                'likedByUsers as liked_by_current_user' => fn (Builder $query) => $query->whereKey($userId),
                'savedByUsers as saved_by_current_user' => fn (Builder $query) => $query->whereKey($userId),
            ]);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if (mb_strlen($term) < self::SEARCH_MIN_LENGTH) {
            return $query;
        }

        // This intentionally keeps the lightweight LIKE-based search isolated.
        // For larger datasets, replace it with a database full-text index/search service.
        return $query->where(function (Builder $query) use ($term) {
            $query->where('title', 'like', "%{$term}%")
                ->orWhere('body', 'like', "%{$term}%")
                ->orWhereHas('user', fn (Builder $query) => $query->where('name', 'like', "%{$term}%"));
        });
    }
}
