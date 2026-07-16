<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function __invoke(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        if ($post->likedByUsers()->whereKey($user->id)->exists()) {
            $post->likedByUsers()->detach($user->id);

            return $this->response($request, $post, false, 'Curtida removida.');
        }

        $post->likedByUsers()->attach($user->id);

        return $this->response($request, $post, true, 'Post curtido.');
    }

    private function response(Request $request, Post $post, bool $liked, string $message): RedirectResponse|JsonResponse
    {
        if (! $request->expectsJson()) {
            return back()->with('status', $message);
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likedByUsers()->count(),
            'message' => $message,
        ]);
    }
}