<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function __invoke(Request $request, Post $post): RedirectResponse
    {
        $user = $request->user();

        if ($post->likedByUsers()->whereKey($user->id)->exists()) {
            $post->likedByUsers()->detach($user->id);
            return back()->with('status', 'Curtida removida.');
        }

        $post->likedByUsers()->attach($user->id);
        return back()->with('status', 'Post curtido.');
    }
}