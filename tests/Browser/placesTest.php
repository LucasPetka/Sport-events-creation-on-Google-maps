<?php

namespace Tests\Browser;

use App\User;
use App\Type;
use App\PlaceQueue;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\RefreshDatabase;



class placesTest extends DuskTestCase
{
    use RefreshDatabase;

    //===USER

    //User can submit new place
    public function testSubmitNewPlace()
    {
        $user = factory(User::class)->create([
            'email' => 'taylor@laravel.com', 'isAdmin' => null
        ]);

        factory(Type::class, 10)->create();
        
        $this->browse(function ($browser) use($user) {
            $browser->loginAs(User::find(1))
                    ->visit('/')
                    ->click('@close_info_modal')
                    ->mouseover('#press')
                    ->moveMouse(0, 200)
                    ->moveMouse(200, 0)
                    ->rightClick()
                    ->press('@add_new_place_button');

            $browser->pause('1000')
                    ->assertSee('Add new sport spot') 
                    ->type('add_new_place_title', "New Place title")
                    ->type('add_new_place_about', "New Place about text")
                    ->select('sport_type')
                    ->click('#add_new_place_btn')
                    ->pause('2000');

            $this->assertDatabaseHas('placequeue', [
                'title' => 'New Place title',
                'about' => 'New Place about text'
            ]);
        });
    }

    //User submits the place and gets error for bad input
    public function testSubmitNewPlace_Error()
    {
        $this->browse(function ($browser) {
            $browser->mouseover('#press')
                    ->moveMouse(0, 20)
                    ->moveMouse(20, 0)
                    ->rightClick()
                    ->press('@add_new_place_button');

            $browser->pause('1000')
                    ->assertSee('Add new sport spot') 
                    ->type('add_new_place_title', "New Place title error")
                    ->click('#add_new_place_btn')
                    ->pause('2000')
                    ->press('@close_addplace_modal');

            $this->assertDatabaseMissing('placequeue', [
                'title' => 'New Place title error',
                'about' => ''
            ]);
        });
    }

    //User check if there is a place in his profile that the submited
    public function testCheck_if_there_is_place_in_profile(){
        $this->browse(function ($browser) {
            $browser->visit('/home')
                    ->assertSee('Profile')
                    ->click('@places_navigation_profile')
                    ->waitForText('New Place title')
                    ->assertSee('New Place title');
        });
    }


    //===ADMIN

    //Admin can submit new place
    public function testSubmitNewPlaceByAdmin()
    {
        $admin = factory(User::class)->create([
            'email' => 'john@laravel.com', 'isAdmin' => '1'
        ]);

        $this->browse(function ($browser2) use($admin) {
            $browser2->loginAs(User::find(2))
                    ->visit('/')
                    ->mouseover('#press')
                    ->moveMouse(0, 200)
                    ->moveMouse(200, 0)
                    ->rightClick()
                    ->press('@add_new_place_button');

            $browser2->pause('1000')
                    ->assertSee('Add new sport spot') 
                    ->type('add_new_place_title', "New admin Place title")
                    ->type('add_new_place_about', "New admin Place about text")
                    ->select('sport_type')
                    ->click('#add_new_place_btn')
                    ->pause('2000');

            $this->assertDatabaseHas('places', [
                'title' => 'New admin Place title',
                'about' => 'New admin Place about text'
            ]);
        });
    }

    //Admin submits the place and gets error for bad input
    public function testSubmitNewPlaceByAdmin_Error()
    {
        $this->browse(function ($browser2) {
            $browser2->mouseover('#press')
                    ->moveMouse(0, 20)
                    ->moveMouse(20, 0)
                    ->rightClick()
                    ->press('@add_new_place_button');

            $browser2->pause('1000')
                    ->assertSee('Add new sport spot') 
                    ->type('add_new_place_title', "New admin Place title error")
                    ->click('#add_new_place_btn')
                    ->pause('2000')
                    ->press('@close_addplace_modal');

            $this->assertDatabaseMissing('placequeue', [
                'title' => 'New admin Place title error',
                'about' => ''
            ]);
        });
    }

    //Admin check if there is a place in his profile that the submited
    public function testCheck_if_there_is_place_in_Admin_profile(){
        $this->browse(function ($browser2) {
            $browser2->visit('/home')
                    ->assertSee('Profile')
                    ->click('@places_navigation_profile')
                    ->pause('1000')
                    ->click('@places_accepted')
                    ->waitForText('New admin Place title')
                    ->assertSee('New admin Place title');
        });
    }

    


}