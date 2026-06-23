<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse
    {
        $post->comments()->create([
            'body' => $request->validated('body'),
            'user_id' => $request->user()->id,
        ]);

        return back()->with('status', 'Comentário publicado com sucesso.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return back()->with('status', 'Comentário excluído com sucesso.');
    }
}