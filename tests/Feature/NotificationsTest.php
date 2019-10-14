<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    public function a_nitification_is_prepared_when_a_subscribed_theard_receives_a_new_reply_is_not_be_a_current_user()
    {
        $theard = create('App\Theard')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $theard->addReply([
            'user_id' => auth()->user()->id,
            'body' => 'Some body text'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $theard->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some body text'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_notifications()
    {
        create(DatabaseNotification::class);

        $this->assertCount(
            1,
            $this->getJson('/profiles/' . auth()->user()->name . '/notifications')->json()
        );
    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        create(DatabaseNotification::class);

        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->unreadNotifications);

            $this->delete("/profiles/{$user->name}/notifications/" . $user->unreadNotifications->first()->id);

            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
