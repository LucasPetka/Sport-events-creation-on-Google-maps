<?php

namespace Tests\Browser;

use App\User;
use App\Type;
use App\Place;
use App\PlaceQueue;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\RefreshDatabase;


class profileAndAdminTest extends DuskTestCase
{
    use RefreshDatabase;

    //==============================================================================
    //=================================PROFILE======================================
    //==============================================================================

    //User can edit his own profile name and sports he likes
    public function testProfileUpdate()
    {

        // $user = factory(User::class)->create([
        //     'email' => 'taylor@laravel.com', 'isAdmin' => null
        // ]);

        // $admin = factory(User::class)->create([
        //     'email' => 'john@laravel.com', 'isAdmin' => '1'
        // ]);

        factory(Type::class)->create([
            'id'=>'111', 'name' => 'Football', 'image' => 'soccerball_1582989356.png' , 'image_h' => 'soccerball-h_1582989356.png'
        ]);

        $this->browse(function ($browser) {
            $browser->loginAs(User::find(2))
                    ->visit('/home')
                    ->click('@update-profile-button')
                    ->waitForText('Edit Profile')
                    ->type('username', "NewUsername")
                    ->click('#sport-checkbox')
                    ->click('@update_profile_submit_btn')
                    ->pause('1000')
                    ->assertSee('NewUsername') 
                    ->pause('2000');

            $this->assertDatabaseHas('users', [
                'name' => 'NewUsername',
            ]);
            
        });
    }

    //==============================================================================
    //=============================SPORT TYPES======================================
    //==============================================================================

    //Admin can edit sport types
    public function testSportTypeEdit()
    {

        factory(Type::class)->create([
            'id'=>'222', 'name' => 'WrongName', 'image' => 'basketball_1585325920.png' , 'image_h' => 'basketball-h_1585325920.png'
        ]);

        $this->browse(function ($browser) {
            $browser->visit('/admin/sporttypes/edit/222')
                    ->type('sport_name', "Basketball")
                    ->click('@sport_type_btn_update')
                    ->pause('2000');

            $this->assertDatabaseHas('types', [
                'name' => 'Basketball',
            ]);
            
        });
    }


    //Admin can add new sport type
    public function testSportTypeAddNew()
    {
        $this->browse(function ($browser) {
            $browser->click('@add_new_sport_type_btn')
                    ->pause('1000')
                    ->waitForText('Add new Sport Type')
                    ->attach('sport_logo', __DIR__.'\images\rugby.png')
                    ->attach('sport_logo_highlighted', __DIR__.'\images\rugby_h.png')
                    ->type('sport_name', 'Rugby')
                    ->click('@add_new_sport_type_btn_submit')
                    ->pause('2000'); 

            $this->assertDatabaseHas(
                'types', [
                'name' => 'Rugby',
            ]);
            
        });
    }

    //==============================================================================
    //=============================SPORT PLACES=====================================
    //==============================================================================

    //Admin can edit place
    public function testPlaceEdit()
    {
        factory(Place::class,5)->create([ 'type'=>'111' ]);


        $this->browse(function ($browser) {
            $browser->visit('/admin/places')
                    ->click('@open_place_edit_modal')
                    ->pause('1000')
                    ->type('title', "New place title")
                    ->type('about', "New place text about")
                    ->click('@place_edit_submit');

                $this->assertDatabaseHas('places', [
                    'title' => 'New place title',
                    'about' => 'New place text about',
                ]);
        });
    }

    // //Admin can delete place
    public function testPlaceDelete()
    {
        $this->browse(function ($browser) {
            $browser->visit('/admin/places')
                    ->click('@place_delete_btn')
                    ->pause('1000');

                $this->assertDatabaseMissing('places', [
                    'title' => 'New place title',
                    'about' => 'New place text about',
                ]);
        });
    }


    //==============================================================================
    //=============================SUBMITED PLACES==================================
    //==============================================================================
    

    //Admin can decline place
    // public function testSubmitedPlaceDecline()
    // {
    //     $submited_place = factory(PlaceQueue::class)->create(['type'=>'111', 'personid'=>'1']);

    //     $this->browse(function ($browser) use ($submited_place) {
    //         $browser->visit('/admin/places_to_confirm')
    //                 ->click('@decline_submited_place')
    //                 ->pause('1000')
    //                 ->assertSee('bryak');

    //         $this->assertDatabaseHas('declined_places', [
    //             'title' =>  $submited_place->title,
    //         ]);
    //     });

    // }

    // //Admin can accept place
    // public function testSubmitedPlaceAccept()
    // {

    //     $submited_place = factory(PlaceQueue::class)->create(['type'=>'111', 'personid'=>'1']);

    //     $this->browse(function ($browser) {
    //         $browser->visit('/admin/places_to_confirm')
    //                 ->click('@accept_submited_place')
    //                 ->pause('1000')
    //                 ->assertSee('bryak');

    //          $this->assertDatabaseMissing('placequeue', [
    //              'title' => $submited_place->title
    //          ]);

    //     });

    // }


    //Admin can delete place
    public function testSubmitedPlaceDelete()
    {

        $submited_place = factory(PlaceQueue::class)->create(['title' => 'Place to delete', 'type'=>'111', 'personid'=>'1']);

        $this->browse(function ($browser) use($submited_place) {
            $browser->visit('/admin/places_to_confirm')
                    ->click('@delete_submited_place')
                    ->pause('1000')
                    ->click('@delete_submited_place')
                    ->pause('1000');

                $this->assertDatabaseMissing('placequeue', [
                    'title' => $submited_place->title,
                    'about' => $submited_place->about,
                ]);
        });
    }
    



}

