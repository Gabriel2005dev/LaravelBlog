<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'Meu primeiro post',
            'body' => 'Conteúdo completo para validar a criação do post.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['title' => 'Meu primeiro post', 'user_id' => $user->id]);
    }

    public function test_only_author_can_update_post(): void
    {
        $post = Post::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($otherUser)->put(route('posts.update', $post), [
            'title' => 'Título invadido',
            'body' => 'Tentativa de alteração por outro usuário.',
        ])->assertForbidden();
    }
}