<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class CommentLikeController extends Controller
{
      public function toggle(
        Request $request,
        Comment $comment
    ): RedirectResponse {
        abort_unless($comment->is_approved, 404);

        abort_unless(
            $comment->parent_id !== null,
            403,
            'Solo puedes dar Me gusta a las respuestas.'
        );

        $like = $comment->likes()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            $comment->likes()->create([
                'user_id' => $request->user()->id,
            ]);
        }

        return back();
    }
}
