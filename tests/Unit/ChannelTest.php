<?php


namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_channel_consists_of_theards()
    {
        $channel = create('App\Channel');
        $theard = create('App\Theard', ['channel_id' => $channel->id]);

        $this->assertTrue($channel->theards->contains($theard));
    }
}
