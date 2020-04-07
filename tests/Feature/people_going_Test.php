<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class people_going_Test extends TestCase
{
    
    use RefreshDatabase;


    //*********************************************************************************************************************************
    //                                                      SUCCESS start
    //*********************************************************************************************************************************


    //=============People Going to event==================
    public function test_people_going_join_event()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => null]);

        //Create simple User
        $admin = factory(User::class)->create(['isAdmin' => '1']);

        $response = $this->post('/api/placequeue?api_token='.$admin->api_token , [
            'place_id' => '48',
            'title' => 'Place to upload',
            'about' => 'Place to upload about',
            'lat' => '52.145648721987',
            'lng' => '21.245648456732',
            'type' => '333',
            'paid' => true,
        ]);

        $response = $this->post('/api/event?api_token='.$user->api_token , [
            'id' => '88',
            'place_id' => '48',
            'title' => 'Event to join',
            'about' => 'Event to join about',
            'time_from' => '2020-5-17 11:30',
            'time_until' => '2020-5-17 20:00',
            'organizator' => $user->name,
        ]);

        $response = $this->post('/api/person?api_token='.$user->api_token , [
            'place_id' => '48',
            'event_id' => '88',
            'person_id' => $user->id,
        ]);

        $this->assertDatabaseHas('people_going', [
            'place_id' => '48',
            'event_id' => '88',
        ]);
    }




}
