<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EventAccept;
use App\Notifications\EventDeclined;

class events_Test extends TestCase
{
    
    use RefreshDatabase;


    //*********************************************************************************************************************************
    //                                                      SUCCESS start
    //*********************************************************************************************************************************


    //=============Upload simple event by user==================
    public function test_upload_event_by_user()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => null]);

        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '111',
            'place_id' => '212',
            'title' => 'Event1',
            'about' => 'Event1 about about',
            'time_from' => '2020-4-17 11:30',
            'time_until' => '2020-4-17 20:00',
            'organizator' => $user->name,
        ]);

        $this->assertDatabaseHas('eventqueue', [
            'place_id' => '212',
            'title' => 'Event1',
            'about' => 'Event1 about about'
        ]);

        $this->assertDatabaseMissing('events', [
            'place_id' => '212',
            'title' => 'Event1',
            'about' => 'Event1 about about'
        ]);
    }

    //=============Upload simple event by admin==================
    public function test_upload_event_by_admin()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => '1']);

        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '112',
            'place_id' => '213',
            'title' => 'Event2',
            'about' => 'Event2 about about',
            'time_from' => '2020-5-17 11:30',
            'time_until' => '2020-5-17 20:00',
            'organizator' => $user->name,
        ]);

        $this->assertDatabaseHas('events', [
            'place_id' => '213',
            'title' => 'Event2',
            'about' => 'Event2 about about'
        ]);

        $this->assertDatabaseMissing('eventqueue', [
            'place_id' => '213',
            'title' => 'Event2',
            'about' => 'Event2 about about'
        ]);
    }

    //=============Update simple event by admin==================
    public function test_edit_event_by_admin()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => '1']);

        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '113',
            'place_id' => '222',
            'title' => 'Event3',
            'about' => 'Event3 about about',
            'time_from' => '2020-5-17 11:30',
            'time_until' => '2020-5-17 20:00',
            'organizator' => $user->name,
        ]);

        $this->assertDatabaseHas('events', [
            'place_id' => '222',
            'title' => 'Event3',
            'about' => 'Event3 about about'
        ]);

        $response = $this->put('/api/event?api_token='.$user->api_token , [
            'id' => '113',
            'place_id' => '222',
            'title' => 'Event33',
            'about' => 'Event33 about',
            'time_from' => '2020-5-17 11:30',
            'time_until' => '2020-5-17 20:00',
            'organizator' => $user->name,
        ]);

        $this->assertDatabaseHas('events', [
            'place_id' => '222',
            'title' => 'Event33',
            'about' => 'Event33 about'
        ]);
    }


    //=============Update simple event by user which before that will be accepted by admin==================
    public function test_edit_event_by_user()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => null]);
        //Create Admin        
        $admin = factory(User::class)->create(['isAdmin' => '1']);

        //Submit event by user
        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '114',
            'place_id' => '444',
            'title' => 'Event5',
            'about' => 'Event5 about about',
            'time_from' => '2020-5-17 11:30',
            'time_until' => '2020-5-17 20:00',
            'organizator' => $user->name,
        ]);

        //Check if user submited event is in queue
        $this->assertDatabaseHas('eventqueue', [
            'place_id' => '444',
            'title' => 'Event5',
            'about' => 'Event5 about about'
        ]);

        //Accept event
        $response = $this->actingAs($admin)->get('/admin/accevent/114');

        //Check if event was accepted
        $this->assertDatabaseHas('events', [
            'place_id' => '444',
            'title' => 'Event5',
            'about' => 'Event5 about about'
        ]);

        //Update event with user
        $response = $this->put('/api/event?api_token='.$user->api_token , [
            'id' => '114',
            'place_id' => '444',
            'title' => 'Event55',
            'about' => 'Event55 about',
            'time_from' => '2020-5-17 11:30',
            'time_until' => '2020-5-17 20:00',
            'organizator' => $user->name,
        ]);

        //Check if event was updated
        $this->assertDatabaseHas('events', [
            'place_id' => '444',
            'title' => 'Event55',
            'about' => 'Event55 about'
        ]);
    }

    //=============Delete simple event by user which before that will be accepted by admin==================
    public function test_delete_event_by_user()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => null]);
        //Create Admin        
        $admin = factory(User::class)->create(['isAdmin' => '1']);

        //Submit event by user
        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '115',
            'place_id' => '555',
            'title' => 'Event6',
            'about' => 'Event6 about about',
            'time_from' => '2020-5-17 11:30',
            'time_until' => '2020-5-17 20:00',
            'organizator' => $user->name,
        ]);

        //Check if user submited event is in queue
        $this->assertDatabaseHas('eventqueue', [
            'place_id' => '555',
            'title' => 'Event6',
            'about' => 'Event6 about about'
        ]);

        //Accept event
        $response = $this->actingAs($admin)->get('/admin/accevent/115');

        //Check if event was accepted
        $this->assertDatabaseHas('events', [
            'place_id' => '555',
            'title' => 'Event6',
            'about' => 'Event6 about about'
        ]);

        //Delete event with user
        $response = $this->delete('/api/event/115?api_token='.$user->api_token);

        //Check if event was updated
        $this->assertDatabaseMissing('events', [
            'id' => '115',
            'place_id' => '555',
            'title' => 'Event55',
            'about' => 'Event55 about'
        ]);
    }

    //=============Event Decline==================
    public function test_decline_event()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => null]);
        //Create Admin        
        $admin = factory(User::class)->create(['isAdmin' => '1']);

        //Submit event by user
        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '116',
            'place_id' => '556',
            'title' => 'Event decline_event',
            'about' => 'Event decline_event about',
            'time_from' => '2020-5-17 11:30',
            'time_until' => '2020-5-17 20:00',
            'organizator' => $user->name,
        ]);

        //Check if user submited event is in queue
        $this->assertDatabaseHas('eventqueue', [
            'place_id' => '556',
            'title' => 'Event decline_event',
            'about' => 'Event decline_event about'
        ]);

        //Accept event
        Notification::fake();
        $response = $this->actingAs($admin)->get('/admin/decevent/116');

        //Check if email and notification were sent to user about declined event
        Notification::assertSentTo([$user],EventDeclined::class);

        //Check if event was accepted
        $this->assertDatabaseHas('declined_events', [
            'place_id' => '556',
            'title' => 'Event decline_event',
            'about' => 'Event decline_event about'
        ]);
  
    }

    //=============Event Accept==================
    public function test_accept_event()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => null]);
        //Create Admin        
        $admin = factory(User::class)->create(['isAdmin' => '1']);

        //Submit event by user
        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '117',
            'place_id' => '557',
            'title' => 'Event accept_event',
            'about' => 'Event accept_event about',
            'time_from' => '2020-5-17 11:30',
            'time_until' => '2020-5-17 20:00',
            'organizator' => $user->name,
        ]);

        //Check if user submited event is in queue
        $this->assertDatabaseHas('eventqueue', [
            'place_id' => '557',
            'title' => 'Event accept_event',
            'about' => 'Event accept_event about'
        ]);

        //Accept event
        Notification::fake();

        $response = $this->actingAs($admin)->get('/admin/accevent/117');
        
        //Check if email and notification were sent to user about declined event
        Notification::assertSentTo([$user],EventAccept::class);

        //Check if event was accepted
        $this->assertDatabaseHas('events', [
            'place_id' => '557',
            'title' => 'Event accept_event',
            'about' => 'Event accept_event about'
        ]);
  
    }


    //*********************************************************************************************************************************
    //                                                      SUCCESS end
    //*********************************************************************************************************************************






    //*********************************************************************************************************************************
    //                                                      ERROR start
    //*********************************************************************************************************************************


    //=============Upload simple event by user============ERROR======
    public function test_upload_event_by_user_ERROR()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => null]);

        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '111',
            'place_id' => '212',
            'title' => 'Event  upload_event_user upload_event_user upload_event_user upload_event_user',
            'about' => '',
            'time_from' => '2020-4-17 11:30',
            'time_until' => '2020-4-17 20:00',
            'organizator' => $user->name,
        ]);

        $this->assertDatabaseMissing('eventqueue', [
            'place_id' => '212',
            'title' => 'Event  upload_event_user upload_event_user upload_event_user upload_event_user',
            'about' => ''
        ]);

        $response->assertSessionHasErrors('title');
        $response->assertSessionHasErrors('about');
    }

    //=============Upload simple event by admin===========ERROR=======
    public function test_upload_event_by_admin_ERROR()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => '1']);

        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '61',
            'place_id' => '7',
            'title' => 'Event upload_event_user_Error_by_time',
            'about' => 'Event upload_event_user_Error_by_time about',
            'time_from' => '2020-5-17 11:30',
            'time_until' => '2020-5-17 20:00',
            'organizator' => $user->name,
        ]);

        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '62',
            'place_id' => '7',
            'title' => 'Event upload_event_user_Error_by_time ERROR',
            'about' => 'Event upload_event_user_Error_by_time about ERROR',
            'time_from' => '2020-5-17 19:30',
            'time_until' => '2020-5-17 21:00',
            'organizator' => $user->name,
        ]);

        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '62',
            'place_id' => '7',
            'title' => 'Event upload_event_user_Error_by_time SUCCESS',
            'about' => 'Event upload_event_user_Error_by_time about SUCCESS',
            'time_from' => '2020-5-17 20:00',
            'time_until' => '2020-5-17 23:00',
            'organizator' => $user->name,
        ]);

        $this->assertDatabaseHas('events', [
            'place_id' => '7',
            'title' => 'Event upload_event_user_Error_by_time',
            'about' => 'Event upload_event_user_Error_by_time about'
        ]);

        $this->assertDatabaseHas('events', [
            'place_id' => '7',
            'title' => 'Event upload_event_user_Error_by_time SUCCESS',
            'about' => 'Event upload_event_user_Error_by_time about SUCCESS'
        ]);

        $this->assertDatabaseMissing('events', [
            'place_id' => '7',
            'title' => 'Event upload_event_user_Error_by_time ERROR',
            'about' => 'Event upload_event_user_Error_by_time about ERROR'
        ]);
    }

    //=============Update simple event by admin==============ERROR====
    public function test_edit_event_by_admin_ERROR()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => '1']);

        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '113',
            'place_id' => '8',
            'title' => 'Event upload_event_user_ERROR upload_event_user_ERROR upload_event_user_ERROR',
            'about' => '',
            'time_from' => '2020-5-17 11:30',
            'time_until' => '2020-5-17 20:00',
            'organizator' => $user->name,
        ]);

        $this->assertDatabaseMissing('events', [
            'place_id' => '8',
            'title' => 'Event upload_event_user_ERROR upload_event_user_ERROR upload_event_user_ERROR',
            'about' => ''
        ]);

        $response->assertSessionHasErrors('title');
        $response->assertSessionHasErrors('about');
    }




    //*********************************************************************************************************************************
    //                                                      ERROR end
    //*********************************************************************************************************************************





}
