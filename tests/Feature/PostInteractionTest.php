<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostInteractionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_like_and_unlike_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)->post(route('posts.like.toggle', $post))->assertRedirect();
        $this->assertDatabaseHas('post_likes', ['post_id' => $post->id, 'user_id' => $user->id]);

        $this->actingAs($user)->post(route('posts.like.toggle', $post))->assertRedirect();
        $this->assertDatabaseMissing('post_likes', ['post_id' => $post->id, 'user_id' => $user->id]);
    }

    public function test_authenticated_user_can_save_and_unsave_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)->post(route('posts.save.toggle', $post))->assertRedirect();
        $this->assertDatabaseHas('saved_posts', ['post_id' => $post->id, 'user_id' => $user->id]);

        $this->actingAs($user)->post(route('posts.save.toggle', $post))->assertRedirect();
        $this->assertDatabaseMissing('saved_posts', ['post_id' => $post->id, 'user_id' => $user->id]);
    }

    public function test_like_toggle_returns_json_for_async_requests(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->postJson(route('posts.like.toggle', $post))
            ->assertOk()
            ->assertJson([
                'liked' => true,
                'likes_count' => 1,
            ]);
    }

    public function test_save_toggle_returns_json_for_async_requests(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->postJson(route('posts.save.toggle', $post))
            ->assertOk()
            ->assertJson([
                'saved' => true,
            ]);
    }

    public function test_liked_and_saved_pages_show_only_related_posts_for_user(): void
    {
        $user = User::factory()->create();
        $liked = Post::factory()->create(['title' => 'Post curtido']);
        $saved = Post::factory()->create(['title' => 'Post salvo']);
        $unrelated = Post::factory()->create(['title' => 'Post alheio']);

        $user->likedPosts()->attach($liked->id);
        $user->savedPosts()->attach($saved->id);

        $this->actingAs($user)->get(route('posts.liked.index'))
            ->assertOk()
            ->assertSee('Post curtido')
            ->assertDontSee('Post salvo')
            ->assertDontSee('Post alheio');

        $this->actingAs($user)->get(route('posts.saved.index'))
            ->assertOk()
            ->assertSee('Post salvo')
            ->assertDontSee('Post curtido')
            ->assertDontSee('Post alheio');
    }

    public function test_guest_cannot_access_protected_interaction_routes(): void
    {
        $post = Post::factory()->create();

        $this->post(route('posts.like.toggle', $post))->assertRedirect(route('login'));
        $this->post(route('posts.save.toggle', $post))->assertRedirect(route('login'));
        $this->get(route('posts.liked.index'))->assertRedirect(route('login'));
        $this->get(route('posts.saved.index'))->assertRedirect(route('login'));
    }
}