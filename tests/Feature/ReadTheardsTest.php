<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadTheardsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->theard = factory('App\Theard')->create();
    }

    /** @test */
    public function a_user_can_view_all_theard()
    {
        $this->get('/questions')
            ->assertSee($this->theard->title);
    }

    /** @test */
    public function a_user_can_view_a_single_theard()
    {
        $this->get($this->theard->path())
            ->assertSee($this->theard->title);
    }

    /** @test */
    public function a_user_can_filter_theards_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $theardInChannel = create('App\Theard', ['channel_id' => $channel->id]);

        $theardNotInChannel = create('App\Theard');

        $this->get('/tagged/' . $channel->slug)
            ->assertSee($theardInChannel->title)
            ->assertDontSee($theardNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_theards_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnCrammer']));

        $theardByJohn = create('App\Theard', ['user_id' => auth()->user()->id]);
        $theardNotByJohn = create('App\Theard');

        $this->get('questions?by=JohnCrammer')
            ->assertSee($theardByJohn->title)
            ->assertDontSee($theardNotByJohn->title);
    }

    /** @test */
    public function a_user_can_filter_theards_by_popularity()
    {
        $theardWithTwoReplies = create('App\Theard');
        create('App\Reply', ['theard_id' => $theardWithTwoReplies->id], 2);

        $theardWithThreeReplies = create('App\Theard');
        create('App\Reply', ['theard_id' => $theardWithThreeReplies->id], 3);

        $theardWithNoRepliese = $this->theard;

        $response = $this->getJson('questions?popular=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }

    /** @test */
    public function a_user_can_filter_by_those_that_are_unanswered()
    {
        $theard = create('App\Theard');

        create('App\Reply', ['theard_id' => $theard->id]);

        $response = $this->getJson('questions?unanswered=1')->json();

        $this->assertCount(1, $response);
    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_theard()
    {
        $theard = create('App\Theard');
        $reply = create('App\Reply', ['theard_id' => $theard->id]);

        $response = $this->getJson($theard->path() . '/replies')->json();

        $this->assertCount(1, $response['data']);
        $this->assertEquals(1, $response['total']);
    }
}
