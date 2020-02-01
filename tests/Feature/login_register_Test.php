<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class login_register_Test extends TestCase
{
    
    use RefreshDatabase;


    public function test_only_logged_in_users_can_see_profile_page()
    {
        $response = $this->get('/home')
            ->assertRedirect('/login');
    }

    public function test_authenticated_users_can_see_profile_page()
    {
        $this->actingAs(factory(User::class)->create());

        $response = $this->get('/home')->assertOk();
    }

    public function test_user_cannot_view_a_login_form_when_authenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/');
    }

    // public function test_user_added_to_database_after_registration()
    // {

    // }
}
