<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $featuredPost = Post::query()
            ->with(['category', 'user', 'tags'])
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->first();

        $posts = Post::query()
            ->with(['category', 'user', 'tags'])
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->when(
                $featuredPost,
                fn($query) => $query->whereKeyNot($featuredPost->id)
            )
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();

        $categories = Category::query()
            ->withCount([
                'posts' => fn($query) => $query
                    ->where('is_published', true),
            ])
            ->having('posts_count', '>', 0)
            ->orderByDesc('posts_count')
            ->limit(10)
            ->get();

        $tags = Tag::query()
            ->withCount([
                'posts' => fn($query) => $query
                    ->where('is_published', true),
            ])
            ->having('posts_count', '>', 0)
            ->orderByDesc('posts_count')
            ->limit(15)
            ->get();

        return view('posts.index', compact(
            'featuredPost',
            'posts',
            'categories',
            'tags',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        /* $post->load(['category', 'user', 'tags']); */

        $post->load([
            'category',
            'user',
            'tags',
            'comments' => function ($query) {
                $query->with([
                    'user',
                    'replies.user',
                    'replies.likes',
                ])->withCount('replies');
            },
        ]);

        $previousPost = Post::query()
            ->where('is_published', true)
            ->where('order', '<', $post->order)
            ->orderByDesc('order')
            ->first();

        $nextPost = Post::query()
            ->where('is_published', true)
            ->where('order', '>', $post->order)
            ->orderBy('order')
            ->first();

        $relatedPosts = Post::query()
            ->with(['category', 'user'])
            ->where('is_published', true)
            ->whereKeyNot($post->id)
            ->when(
                $post->category_id,
                fn($query) => $query->where('category_id', $post->category_id)
            )
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('posts.show', compact(
            'post',
            'previousPost',
            'nextPost',
            'relatedPosts'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
