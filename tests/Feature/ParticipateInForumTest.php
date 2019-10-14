<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unauth_users_may_not_add_replies()
    {
        $this->withExceptionHandling()
            ->post('/questions/some-question/replies', [])
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_auth_user_may_participate_in_forum_theards()
    {
        $this->be($user = factory('App\User')->create());

        $theard = factory('App\Theard')->create();

        $reply = factory('App\Reply')->make();
        $this->post($theard->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $theard = factory('App\Theard')->create();
        $reply = factory('App\Reply')->make(['body' => null]);

        $this->post($theard->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('/login');

        $this->signIn()->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->user()->id]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->theard->fresh()->replies_count);
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('/login');

        $this->signIn()->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->user()->id]);

        $this->patch("/replies/{$reply->id}", ['body' => 'You have changed foo!']);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => 'You have changed foo!']);
    }
}
