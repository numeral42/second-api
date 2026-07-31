<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Api
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

/*     public function getRouteKeyName(): string
{
    return 'slug';
} */

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

}
