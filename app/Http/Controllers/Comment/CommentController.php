<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
     public function store(
        StoreCommentRequest $request,
        Post $post
    ): RedirectResponse {
        $data = $request->validated();

        $parent = null;

        if (! empty($data['parent_id'])) {
            $parent = Comment::query()
                ->whereKey($data['parent_id'])
                ->where('post_id', $post->id)
                ->whereNull('parent_id')
                ->firstOrFail();
        }

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $parent?->id,
            'body' => trim($data['body']),
            'is_approved' => true,
        ]);

        return back()->with('swal', [
            'icon' => 'success',
            'title' => $parent
                ? 'Respuesta publicada'
                : 'Comentario publicado',
            'text' => $parent
                ? 'Tu respuesta se publicó correctamente.'
                : 'Tu comentario se publicó correctamente.',
        ]);
    }
}
