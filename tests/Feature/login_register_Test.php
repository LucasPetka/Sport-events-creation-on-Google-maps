<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class login_register_Test extends TestCase
{
    
    use RefreshDatabase, WithFaker;

    //===========================LOGIN======================================

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

    public function test_login_displays_validation_errors()
    {
        $response = $this->post('/login', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }


    //===========================REGISTER======================================


    public function test_register_creates_and_authenticates_a_user()
    {
        $name = $this->faker->name;
        $email = $this->faker->safeEmail;
        $password = $this->faker->password(8);

        $response = $this->post('register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertRedirect('/email/verify');

        $user = User::where('email', $email)->where('name', $name)->first();
        $this->assertNotNull($user);

        $this->assertAuthenticatedAs($user);
    }

    public function test_register_displays_validation_errors()
    {
        $response = $this->post('register', [
            'name' => 'JMac',
            'email' => 'jmac@example.com',
            'password' => 'p',
            'password_confirmation' => 'p',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }



    
}
