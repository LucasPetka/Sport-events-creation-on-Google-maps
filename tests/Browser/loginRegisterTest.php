<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;



class loginRegisterTest extends DuskTestCase
{
    
    use DatabaseMigrations;


    //User input wrong information into log in form and gets error alert
    public function testloginUserError()
    {
        $user = factory(User::class)->create([
            'email' => 'james@laravel.com',
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'wrongPass')
                    ->press('Login')
                    ->assertPathIs('/login')
                    ->assertSee('These credentials do not match our records.');
        });
    }

    //User tries to register and gets alerts about wrong input
    public function testRegisterUserError()
    {
        $user = factory(User::class)->create([
            'email' => 'taylor@laravel.com',
        ]);

        $this->browse(function ($browser) {
            $browser->visit('/register')
                    ->type('name', "John")
                    ->type('email', "taylor@laravel.com")
                    ->type('password', 'password')
                    ->type('password_confirmation', 'wrongPass')
                    ->press('Register')
                    ->assertPathIs('/register')
                    ->assertSee('The email has already been taken.')
                    ->assertSee('The password confirmation does not match.');
        });
    }


    //User can login and then get redirected to profile
    public function testloginUser()
    {
        $user = factory(User::class)->create([
            'email' => 'taylor@laravel.com',
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/home')
                    ->assertSee('Profile');
        });
    }

    //User can register and go his profile
    public function testRegisterUser()
    {
        $this->browse(function ($browser) {
            $browser->visit('/register')
                    ->type('name', "John")
                    ->type('email', "john@example.com")
                    ->type('password', 'password')
                    ->type('password_confirmation', 'password')
                    ->press('Register')
                    ->assertPathIs('/email/verify')
                    ->click('@profile_dropdown')
                    ->click('@profile_nav')
                    ->assertSee('Profile')
                    ->assertPathIs('/home');
        });
    }



}