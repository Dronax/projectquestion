<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_subscribe_to_theards()
    {
        $this->signIn();

        $theard = create('App\Theard');

        $this->post($theard->path() . '/subscriptions');

        $this->assertCount(1, $theard->fresh()->subscriptions);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_theards()
    {
        $this->signIn();

        $theard = create('App\Theard');

        $theard->subscribe();

        $this->delete($theard->path() . '/subscriptions');

        $this->assertCount(0, $theard->subscriptions);
    }
}
