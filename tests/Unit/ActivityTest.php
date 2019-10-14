<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Activity;
use Carbon\Carbon;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_records_activity_when_a_theard_is_created()
    {
        $this->signIn();

        $theard = create('App\Theard');

        $this->assertDatabaseHas('activities', [
            'type' => 'created_theard',
            'user_id' => auth()->user()->id,
            'subject_id' => $theard->id,
            'subject_type' => 'App\Theard',
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $theard->id);
    }

    /** @test */
    public function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    public function it_fetches_a_feed_for_any_user()
    {
        $this->signIn();

        create('App\Theard', ['user_id' => auth()->user()->id], 2);

        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
