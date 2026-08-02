<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Api
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'body',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->where('is_approved', true)
            ->oldest();
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function likedByUser(?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        return $this->likes->contains('user_id', $userId);
    }

    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }
}
