<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserCanViewProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_can_view_user_profiles()
    {
        $this->withExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');

        $post = factory(Post::class)->create();

        $response = $this->get('/api/users/' . $user->id);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type' => 'users',
                    'user_id' => $user->id,
                    'attributes' => [
                        'name' => $user->name,
                    ]
                ],
                'links' => [
                    'self' => url('/users/' . $user->id),
                ]
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_fetch_post_for_a_profile()
    {
        $this->withExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(), 'api');

        $post = factory(Post::class)->create(['user_id' => $user->id]);

        $response = $this->get('/api/users/' . $user->id . '/posts');
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'type' => 'posts',
                            'post_id' => $post->id,
                            'attributes' => [
                                'body' => $post->body,
                                'image' => $post->image,
                                'posted_at' => $post->created_at->diffForHumans(),
                                'posted_by' => [
                                    'data' => [
                                        'attributes' => [
                                            'name' => $user->name
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'links' => [
                            'self' => url('/posts/' . $post->id),
                        ]
                    ]
                ]
            ]);

    }
}
