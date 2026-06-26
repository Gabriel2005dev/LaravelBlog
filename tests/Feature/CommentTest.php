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

    public function test_comment_body_is_normalized_before_saving(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)->post(route('posts.comments.store', $post), [
            'body' => "  Primeira linha   com espaços  \r\n\r\n   Segunda linha  ",
        ])->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'body' => "Primeira linha com espaços\nSegunda linha",
        ]);
    }

    public function test_non_author_cannot_delete_comment_unless_post_author(): void
    {
        $comment = Comment::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)->delete(route('comments.destroy', $comment))->assertForbidden();
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }

    public function test_post_author_can_delete_comment_on_their_post(): void
    {
        $comment = Comment::factory()->create();

        $this->actingAs($comment->post->user)->delete(route('comments.destroy', $comment))->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_comment_author_can_delete_comment(): void
    {
        $comment = Comment::factory()->create();

        $this->actingAs($comment->user)->delete(route('comments.destroy', $comment))->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}