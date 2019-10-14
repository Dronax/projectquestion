<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_has_a_profile()
    {
        $user = create('App\User');

        $this->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }

    /** @test */
    public function profiles_display_all_theards_created_by_the_associated_user()
    {
        $this->signIn();

        $theard = create('App\Theard', ['user_id' => auth()->user()->id]);

        $this->get('/profiles/' . auth()->user()->name)
                ->assertSee($theard->title)
                ->assertSee($theard->body);
    }
}
