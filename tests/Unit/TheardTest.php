<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TheardTest extends TestCase
{
    use DatabaseMigrations;

    protected $theard;

    public function setUp()
    {
        parent::setUp();

        $this->theard = create('App\Theard');
    }

    /** @test */
    public function a_theard_have_a_string_path()
    {
        $theard = create('App\Theard');

        $this->assertEquals('/theards/' . $theard->channel->slug . '/' . $theard->id, $theard->path());
    }

    /** @test */
    public function a_theard_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->theard->creator);
    }

    /** @test */
    public function a_theard_with_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->theard->replies);
    }

    /** @test */
    public function a_theard_can_add_a_reply()
    {
        $this->theard->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->theard->replies);
    }

    /** @test */
    public function a_theard_belongs_to_a_channel()
    {
        $theard = create('App\Theard');

        $this->assertInstanceOf('App\Channel', $theard->channel);
    }

    /** @test */
    public function a_theard_can_be_subscribed_to()
    {
        $theard = create('App\Theard');

        $theard->subscribe($userId = 1);

        $this->assertEquals(1, $theard->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    public function a_theard_can_be_unsubscribed_to()
    {
        $theard = create('App\Theard');

        $theard->subscribe($userId = 1);

        $theard->unsubscribe($userId = 1);

        $this->assertCount(0, $theard->subscriptions);
    }

    /** @test */
    public function it_knowns_if_the_auth_user_is_subscribed_to_it()
    {
        $theard = create('App\Theard');

        $this->signIn();

        $this->assertFalse($theard->isSubscribedTo);

        $theard->subscribe();

        $this->assertTrue($theard->isSubscribedTo);
    }
}
