<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SavedPostController extends Controller
{
    public function __invoke(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        if ($post->savedByUsers()->whereKey($user->id)->exists()) {
            $post->savedByUsers()->detach($user->id);

            return $this->response($request, false, 'Post removido dos salvos.');
        }

        $post->savedByUsers()->attach($user->id);

        return $this->response($request, true, 'Post salvo.');
    }

    private function response(Request $request, bool $saved, string $message): RedirectResponse|JsonResponse
    {
        if (! $request->expectsJson()) {
            return back()->with('status', $message);
        }

        return response()->json([
            'saved' => $saved,
            'message' => $message,
        ]);
    }
}