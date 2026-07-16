<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post): RedirectResponse|JsonResponse
    {
        $comment = $post->comments()->create([
            'body' => $request->validated('body'),
            'user_id' => $request->user()->id,
        ])->load('user');

        if (! $request->expectsJson()) {
            return back()->with('status', 'Comentário publicado com sucesso.');
        }

        return response()->json([
            'comment' => $this->commentPayload($comment),
            'comments_count' => $post->comments()->count(),
            'message' => 'Comentário publicado com sucesso.',
        ], 201);
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return back()->with('status', 'Comentário excluído com sucesso.');
    }

    private function commentPayload(Comment $comment): array
    {
        $user = $comment->user;

        return [
            'id' => $comment->id,
            'body' => $comment->body,
            'authorName' => auth()->id() === $comment->user_id ? 'Você' : $user->name,
            'authorAvatar' => $user->avatar
                ? asset('storage/' . $user->avatar)
                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name),
            'createdAt' => $comment->created_at->diffForHumans(),
            'canDelete' => auth()->check() && auth()->user()->can('delete', $comment),
            'deleteUrl' => route('comments.destroy', $comment),
        ];
    }
}