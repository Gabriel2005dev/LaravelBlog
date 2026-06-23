<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_comment_on_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)->post(route('posts.comments.store', $post), [
            'body' => 'Comentário de teste.',
        ])->assertRedirect();

        $this->assertDatabaseHas('comments', ['post_id' => $post->id, 'user_id' => $user->id]);
    }

    public function test_comment_author_can_delete_comment(): void
    {
        $comment = Comment::factory()->create();

        $this->actingAs($comment->user)->delete(route('comments.destroy', $comment))->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}