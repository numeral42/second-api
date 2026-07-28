<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\CreatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EditPostRequest;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request)
    {
        $post = Post::create([
            ...$request->validated(),
            'user_id' => Auth::id(),
            'order' => (Post::max('order') ?? 0) + 1,
        ]);

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Post creado exitosamente.',
                'text' => 'El post ha sido creado correctamente.',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = \App\Models\Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditPostRequest $request, Post $post)
    {

        Storage::disk('public')->put('images/posts/', $request->file('image'));

        $post->update($request->validated());

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Post actualizado exitosamente.',
                'text' => 'El post ha sido actualizado correctamente.',
            ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
