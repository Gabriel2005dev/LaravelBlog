<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::with(['user', 'comments.user'])
            ->withCount(['comments', 'likedByUsers as likes_count'])
            ->withExists([
                'likedByUsers as liked_by_current_user' => fn ($query) => $query->whereKey(auth()->id()),
                'savedByUsers as saved_by_current_user' => fn ($query) => $query->whereKey(auth()->id()),
            ])
            ->latest()
            ->paginate(10);

        return view('feed.index', compact('posts'));
    }


    public function liked(): View
    {
        $posts = request()->user()->likedPosts()
            ->with(['user', 'comments.user'])
            ->withCount(['comments', 'likedByUsers as likes_count'])
            ->withExists([
                'likedByUsers as liked_by_current_user' => fn ($query) => $query->whereKey(auth()->id()),
                'savedByUsers as saved_by_current_user' => fn ($query) => $query->whereKey(auth()->id()),
            ])
            ->latest('post_likes.created_at')
            ->paginate(10);

        return view('feed.index', [
            'posts' => $posts,
            'title' => 'Posts curtidos'
        ]);
    }


    public function saved(): View
    {
        $posts = request()->user()->savedPosts()
            ->with(['user', 'comments.user'])
            ->withCount(['comments', 'likedByUsers as likes_count'])
            ->withExists([
                'likedByUsers as liked_by_current_user' => fn ($query) => $query->whereKey(auth()->id()),
                'savedByUsers as saved_by_current_user' => fn ($query) => $query->whereKey(auth()->id()),
            ])
            ->latest('saved_posts.created_at')
            ->paginate(10);

        return view('feed.index', [
            'posts' => $posts,
            'title' => 'Posts salvos'
        ]);
    }


    public function store(StorePostRequest $request): RedirectResponse
    {
        $request->user()->posts()->create([
            ...$request->validated(),
            'slug' => $this->uniqueSlug(
                $request->string('title')->toString()
            ),
        ]);

        return redirect()
            ->route('feed')
            ->with('status', 'Publicação criada com sucesso.');
    }


    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $post->update([
            ...$request->validated(),
            'slug' => $this->uniqueSlug(
                $request->string('title')->toString(),
                $post
            ),
        ]);

        return redirect()
            ->route('feed')
            ->with('status', 'Publicação atualizada com sucesso.');
    }


    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()
            ->route('feed')
            ->with('status', 'Publicação excluída com sucesso.');
    }


    private function uniqueSlug(string $title, ?Post $ignore = null): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $counter = 2;

        while (
            Post::where('slug', $slug)
                ->when(
                    $ignore,
                    fn ($query) => $query->whereKeyNot($ignore)
                )
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }
}