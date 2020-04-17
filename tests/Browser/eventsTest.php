<?php

namespace Tests\Browser;

use App\User;
use App\Type;
use App\Place;
use App\PlaceQueue;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\RefreshDatabase;


class eventsTest extends DuskTestCase
{
    use RefreshDatabase;

    //======USER

    //User can submit new place
    public function testSubmitNewEvent()
    {
        $user = factory(User::class)->create([
            'email' => 'taylor@laravel.com', 'isAdmin' => null
        ]);

        factory(Place::class)->create([
            'id' => '147', 'lat' => '54.894787', 'lng' => '23.909824', 'type' => '111'
        ]);

        factory(Type::class)->create([
           'id'=>'111', 'name' => 'Football', 'image' => 'soccerball_1582989356.png' , 'image_h' => 'soccerball-h_1582989356.png'
        ]);

        $this->browse(function ($browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/')
                    ->click('@close_info_modal')
                    ->type('autocomplete_gmap', "Kaunas")
                    ->pause(500)->keys('#autocomplete_gmap', ['{ARROW_DOWN}'])
                    ->keys('#autocomplete_gmap', ['{ENTER}'])
                    ->mouseover('#press')
                    ->moveMouse(0, 30)
                    ->moveMouse(25, 0);
                    
            $browser->pause('1000')
                    ->click()
                    ->pause('3000')
                    ->click('@add_new_event_btn')
                    ->pause('2000')
                    ->type('title', "New event test")
                    ->type('about', "New event test about")
                    ->click('@add_new_event_submit_button')
                    ->waitForText('Congrats!!')
                    ->assertSee('Congrats!!');

            $this->assertDatabaseHas('eventqueue', [
                'title' => 'New event test',
                'about' => 'New event test about'
            ]);
            
        });
    }

    //User can submit new event Error
    public function testSubmitNewEvent_Error()
    {

        $this->browse(function ($browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/')
                    ->type('autocomplete_gmap', "Kaunas")
                    ->pause(500)->keys('#autocomplete_gmap', ['{ARROW_DOWN}'])
                    ->keys('#autocomplete_gmap', ['{ENTER}'])
                    ->mouseover('#press')
                    ->moveMouse(0, 30)
                    ->moveMouse(25, 0);
                    
            $browser->pause('1000')
                    ->click()
                    ->pause('3000')
                    ->click('@add_new_event_btn')
                    ->pause('2000')
                    ->type('title', "New event test")
                    ->type('about', "")
                    ->click('@add_new_event_submit_button')
                    ->pause('1000');

            $this->assertDatabaseMissing('eventqueue', [
                'title' => 'New event test',
                'about' => ''
            ]);
            
        });
    }

    //User check if there is a event in his profile that the submited
    public function testCheck_if_there_is_event_in_profile(){
        $this->browse(function ($browser) {
            $browser->visit('/home')
                    ->assertSee('Profile')
                    ->click('@events_navigation_profile')
                    ->waitForText('New event test')
                    ->assertSee('New event test about');
        });
    }


    //=========ADMIN

    //Admin can submit new place
    public function testSubmitNewEventAdmin()
    {
        $user = factory(User::class)->create([
            'email' => 'john@laravel.com', 'isAdmin' => '1'
        ]);

        $this->browse(function ($browser) {
            $browser->loginAs(User::find(2))
                    ->visit('/')
                    ->type('autocomplete_gmap', "Kaunas")
                    ->pause(500)->keys('#autocomplete_gmap', ['{ARROW_DOWN}'])
                    ->keys('#autocomplete_gmap', ['{ENTER}'])
                    ->mouseover('#press')
                    ->moveMouse(0, 30)
                    ->moveMouse(25, 0);
                    
            $browser->pause('1000')
                    ->click()
                    ->pause('3000')
                    ->click('@add_new_event_btn')
                    ->pause('2000')
                    ->type('title', "New event test admin")
                    ->type('about', "New event test admin about")
                    ->click('@add_new_event_submit_button')
                    ->pause('1000')
                    ->waitForText('Congrats!!')
                    ->assertSee('Congrats!!');


            $this->assertDatabaseHas('events', [
                'title' => 'New event test admin',
                'about' => 'New event test admin about'
            ]);
            
        });
    }

    //Join an event
    public function testJoinAnEvent()
    {
        $this->browse(function ($browser) {

            $user = User::find(2);

            $browser->pause('1000')
                    ->click('@join_an_event_btn')
                    ->waitForText('You have joined an event !')
                    ->assertSee('You have joined an event !');

            $this->assertDatabaseHas('people_going', [
                'place_id' => '147',
                'event_id' => '1',
                'person_id' => $user->id
            ]);
            
        });
    }

     //Leave an event
     public function testLeaveAnEvent()
     {
         $this->browse(function ($browser) {
 
             $user = User::find(2);
 
             $browser->pause('5000')
                     ->click('@leave_an_event_btn')
                     ->waitForText('You left the event !')
                     ->assertSee('You left the event !');

             $this->assertDatabaseMissing('people_going', [
                'place_id' => '147',
                'event_id' => '1',
                'person_id' => $user->id
             ]);
             
         });
     }

    

     //Edit an event
     public function testEditAnEvent()
     {
         $this->browse(function ($browser) {
 
            $browser->click('@edit_event_btn')
                    ->pause('1000')
                    ->waitForText('Edit Event')
                    ->type('edit_event_title', "New event test admin edited")
                    ->type('edit_event_about', "New event test admin edited about")
                    ->click('@edit_event_submit_btn')
                    ->pause('1000');

                    $this->assertDatabaseHas('events', [
                        'title' => 'New event test admin edited',
                        'about' => 'New event test admin edited about'
                    ]); 
                    
            });
     }


    //Admin can submit new event Error
    public function testSubmitNewEventAdminError()
    {

        $this->browse(function ($browser) {
            $browser->loginAs(User::find(2))
                    ->visit('/')
                    ->type('autocomplete_gmap', "Kaunas")
                    ->pause(500)->keys('#autocomplete_gmap', ['{ARROW_DOWN}'])
                    ->keys('#autocomplete_gmap', ['{ENTER}'])
                    ->mouseover('#press')
                    ->moveMouse(0, 30)
                    ->moveMouse(25, 0);
                    
            $browser->pause('1000')
                    ->click()
                    ->pause('3000')
                    ->click('@add_new_event_btn')
                    ->pause('2000')
                    ->type('title', "New event test admin error")
                    ->type('about', "")
                    ->click('@add_new_event_submit_button')
                    ->pause('1000');

            $this->assertDatabaseMissing('events', [
                'title' => 'New event test admin error',
                'about' => ''
            ]);
            
        });
    }

    //Admin check if there is a event in his profile that the submited
    public function testCheck_if_there_is_event_in_profile_Admin(){
        $this->browse(function ($browser) {
            $browser->visit('/home')
                    ->assertSee('Profile')
                    ->click('@events_navigation_profile')
                    ->pause('1000')
                    ->click('@events_accepted')
                    ->waitForText('New event test admin edited')
                    ->assertSee('New event test admin edited about');
        });
    }

    //Open event page and use livechat
    public function testOpenEventPageAndChatWithOtherUsers(){
        $this->browse(function ($browser, $browser2) {
            $browser->visit('/event/1/New%20event%20test%20admin%20edited')
                    ->type('message_input', "Hey man!")
                    ->keys('#message_input', ['{ENTER}'])
                    ->pause('1000');

            $admin = User::find(2);

            $browser2->loginAs(User::find(1))
                    ->visit('/event/1/New%20event%20test%20admin%20edited')
                    ->pause('1200')
                    ->assertSee('Hey man!');

            $this->assertDatabaseHas('messages', [
                'message' => 'Hey man!',
                'event_id' => '1',
                'user_id' => $admin->id
            ]); 
        });
    }

     //Open event page and use livechat Error
     public function testOpenEventPageAndChatWithOtherUsersERROR(){
        $this->browse(function ($browser, $browser2) {
            $browser->visit('/event/1/New%20event%20test%20admin%20edited')
                    ->type('message_input', "")
                    ->keys('#message_input', ['{ENTER}'])
                    ->pause('1000');

            $browser2->loginAs(User::find(1))
                    ->visit('/event/1/New%20event%20test%20admin%20edited')
                    ->pause('1200')
                    ->assertSee('Hey man!');
        });
    }

     //Join an event and check if its in your profile
     public function testJoinAnEventAndShowInParticipation()
     {
         $this->browse(function ($browser) {
 
             $browser->pause('1000')
                     ->click('@join_an_event_btn_in_event')
                     ->waitForText('You have joined an event !')
                     ->assertSee('You have joined an event !')
                     ->visit('/home')
                     ->waitForText('New event test admin edited')
                     ->assertSee('New event test admin edited about');
         });
     }


    

}