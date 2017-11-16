<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function guest_may_not_create_threads()
    {
        // Given
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());


        // When

        // Then

     }

    /** @test */
    public function an_authenticated_user_can_create_threads()
    {
        // Given
        $this->signIn();

        // When
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());
        // Then
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
     }


}
