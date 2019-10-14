<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Activity;

class CreateTheardsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_may_not_create_theards()
    {
        $this->withExceptionHandling();

        // $this->post('/theards')
        //     ->assertRedirect('/login');

        $this->get('/questions/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_auth_user_can_create_new_forum_theards()
    {
        $this->signIn();

        $theard = make('App\Theard');

        $resp = $this->post('/questions', $theard->toArray());

        $response = $this->get($resp->headers->get('Location'));

        $response->assertSee($theard->title)
            ->assertSee($theard->body);
    }

    /** @test */
    public function a_theard_requires_a_title()
    {
        $this->publishTheard(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_theard_requires_a_body()
    {
        $this->publishTheard(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_theard_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishTheard(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishTheard(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function unauthorized_cannot_delete_theards()
    {
        $this->withExceptionHandling();

        $theard = create('App\Theard');

        $this->delete($theard->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($theard->path())->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_theards()
    {
        $this->signIn();

        $theard = create('App\Theard', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['theard_id' => $theard->id]);

        $response = $this->json('DELETE', $theard->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('theards', ['id' => $theard->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
    }

    public function publishTheard($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $theard = make('App\Theard', $overrides);

        return $this->post('/questions', $theard->toArray());
    }
}
