<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SavedPostController extends Controller
{
    public function __invoke(Request $request, Post $post): RedirectResponse
    {
        $user = $request->user();

        if ($post->savedByUsers()->whereKey($user->id)->exists()) {
            $post->savedByUsers()->detach($user->id);
            return back()->with('status', 'Post removido dos salvos.');
        }

        $post->savedByUsers()->attach($user->id);
        return back()->with('status', 'Post salvo.');
    }
}