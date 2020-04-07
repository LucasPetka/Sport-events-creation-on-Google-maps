<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PlaceAccept;
use App\Notifications\PlaceDeclined;

class places_Test extends TestCase
{
    
    use RefreshDatabase;


    //*********************************************************************************************************************************
    //                                                      SUCCESS start
    //*********************************************************************************************************************************


    //==========User add news place and it goes to placequeue which admins need to look at before accepting=====================
    public function test_upload_place_by_user()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => null]);

        $response = $this->post('/api/placequeue?api_token='.$user->api_token , [
            'place_id' => '',
            'title' => 'Place1',
            'about' => 'place1 about about',
            'lat' => '52.145648721987',
            'lng' => '21.245648456732',
            'type' => '333',
            'paid' => true,
            'personid' => $user->id,
        ]);

        $this->assertDatabaseHas('placequeue', [
            'title' => 'Place1',
            'about' => 'place1 about about'
        ]);
    }

    //==========Admin upload place and it goes straight into MAP=====================
    public function test_upload_place_by_admin()
    {
        //Create Admin
        $user = factory(User::class)->create(['isAdmin' => '1']);

        $response = $this->post('/api/placequeue?api_token='.$user->api_token , [
            'place_id' => '',
            'title' => 'Place1',
            'about' => 'place1 about about',
            'lat' => '52.145648721987',
            'lng' => '21.245648456732',
            'type' => '333',
            'paid' => true,
        ]);

        $this->assertDatabaseHas('places', [
            'title' => 'Place1',
            'about' => 'place1 about about'
        ]);
    }

    //==========Admin deletes place from MAP=====================
    public function test_delete_place_by_admin()
    {
        //Create Admin
        $user = factory(User::class)->create(['isAdmin' => '1']);

        //add new place
        $response = $this->post('/api/placequeue?api_token='.$user->api_token , [
            'place_id' => '999',
            'title' => 'Place1',
            'about' => 'place1 about about',
            'lat' => '52.145648721987',
            'lng' => '21.245648456732',
            'type' => '333',
            'paid' => true,
        ]);

        $this->assertDatabaseHas('places', [
            'id' => '999',
            'title' => 'Place1',
            'about' => 'place1 about about'
        ]);

        //delete place
        $response = $this->delete('/api/place/999?api_token='.$user->api_token);

        $this->assertDatabaseMissing('places', [
            'id' => '999',
            'title' => 'Place1',
            'about' => 'place1 about about'
        ]);
    }


    //==========Admin updates place=====================
    public function test_updates_place_by_admin()
    {
        //Create Admin
        $user = factory(User::class)->create(['isAdmin' => '1']);

        //add new place
        $response = $this->post('/api/placequeue?api_token='.$user->api_token , [
            'place_id' => '999',
            'title' => 'Place1',
            'about' => 'place1 about about',
            'lat' => '52.145648721987',
            'lng' => '21.245648456732',
            'type' => '333',
            'paid' => true,
        ]);

        $this->assertDatabaseHas('places', [
            'id' => '999',
            'title' => 'Place1',
            'about' => 'place1 about about'
        ]);

        //update place
        $response = $this->put('/admin/updateplace', [
            'place_id' => '999',
            'title' => 'Place333',
            'about' => 'place333 about about',
            'lat' => '52.145648721987',
            'lng' => '21.245648456732',
            'type' => '333',
            'paid' => true,
        ]);

        $this->assertDatabaseHas('places', [
            'id' => '999',
            'title' => 'Place333',
            'about' => 'place333 about about',
        ]);
    }

        //=============Place Decline==================
    public function test_decline_place()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => null]);
        //Create Admin        
        $admin = factory(User::class)->create(['isAdmin' => '1']);

        //Submit place by user
        $response = $this->post('/api/placequeue?api_token='.$user->api_token , [
            'place_id' => '44',
            'title' => 'Place place_decline',
            'about' => 'Place place_decline about',
            'lat' => '52.145648721987',
            'lng' => '21.245648456732',
            'type' => '333',
            'paid' => true,
        ]);

        //Check if user submited place is in queue
        $this->assertDatabaseHas('placequeue', [
            'title' => 'Place place_decline',
            'about' => 'Place place_decline about'
        ]);

        //Accept place
        Notification::fake();
        $response = $this->actingAs($admin)->get('/admin/decplace/44');

        //Check if email and notification were sent to user about declined place
        Notification::assertSentTo([$user],PlaceDeclined::class);

        //Check if place was accepted
        $this->assertDatabaseHas('declined_places', [
            'title' => 'Place place_decline',
            'about' => 'Place place_decline about'
        ]);

        $this->assertDatabaseMissing('placequeue', [
            'title' => 'Place place_decline',
            'about' => 'Place place_decline about'
        ]);
  
    }

    //=============Place Accept==================
    public function test_accept_place()
    {
         //Create simple User
         $user = factory(User::class)->create(['isAdmin' => null]);
         //Create Admin        
         $admin = factory(User::class)->create(['isAdmin' => '1']);
 
         //Submit place by user
         $response = $this->post('/api/placequeue?api_token='.$user->api_token , [
             'place_id' => '45',
             'title' => 'Place place_accept',
             'about' => 'Place place_accept about',
             'lat' => '52.145648721987',
             'lng' => '21.245648456732',
             'type' => '333',
             'paid' => true,
         ]);
 
         //Check if user submited place is in queue
         $this->assertDatabaseHas('placequeue', [
             'title' => 'Place place_accept',
             'about' => 'Place place_accept about'
         ]);
 
         //Accept place
         Notification::fake();
         $response = $this->actingAs($admin)->get('/admin/accplace/45');
 
         //Check if email and notification were sent to user about declined place
         Notification::assertSentTo([$user],PlaceAccept::class);

         $this->assertDatabaseMissing('placequeue', [
            'title' => 'Place place_decline',
            'about' => 'Place place_decline about'
        ]);
 
    }

    //*********************************************************************************************************************************
    //                                                      SUCCESS end
    //*********************************************************************************************************************************




    //*********************************************************************************************************************************
    //                                                      ERROR start
    //*********************************************************************************************************************************


    //==========User add news place and it goes to placequeue which admins need to look at before accepting=============ERROR========
    public function test_upload_place_by_user_ERROR()
    {
        //Create simple User
        $user = factory(User::class)->create(['isAdmin' => null]);

        $response = $this->post('/api/placequeue?api_token='.$user->api_token , [
            'place_id' => '',
            'title' => 'Placeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee',
            'about' => '',
            'lat' => '52.145648721987',
            'lng' => '21.245648456732',
            'type' => '333',
            'paid' => true,
            'personid' => $user->id,
        ]);

        $this->assertDatabaseMissing('placequeue', [
            'title' => 'Placeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee',
            'about' => ''
        ]);

        $response->assertSessionHasErrors('title');
        $response->assertSessionHasErrors('about');
    }

    //==========Admin upload place and it goes straight into MAP==============ERROR=======
    public function test_upload_place_by_admin_ERROR()
    {
        //Create Admin
        $user = factory(User::class)->create(['isAdmin' => '1']);

        $response = $this->post('/api/placequeue?api_token='.$user->api_token , [
            'place_id' => '',
            'title' => 'Placeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee',
            'about' => '',
            'lat' => '52.145648721987',
            'lng' => '21.245648456732',
            'type' => '333',
            'paid' => true,
        ]);

        $this->assertDatabaseMissing('placequeue', [
            'title' => 'Placeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee',
            'about' => ''
        ]);

        $response->assertSessionHasErrors('title');
        $response->assertSessionHasErrors('about');
    }

    //==========Admin deletes place from MAP==============ERROR=======
    public function test_delete_place_by_admin_ERROR()
    {
        //Create Admin
        $user = factory(User::class)->create(['isAdmin' => '1']);

        //add new place
        $response = $this->post('/api/placequeue?api_token='.$user->api_token , [
            'place_id' => '889',
            'title' => 'Place1',
            'about' => 'place1 about about',
            'lat' => '52.145648721987',
            'lng' => '21.245648456732',
            'type' => '333',
            'paid' => true,
        ]);

        $this->assertDatabaseHas('places', [
            'id' => '889',
            'title' => 'Place1',
            'about' => 'place1 about about'
        ]);

        //delete place
        $response = $this->delete('/api/place/99?api_token='.$user->api_token);

        $this->assertDatabaseHas('places', [
            'id' => '889',
            'title' => 'Place1',
            'about' => 'place1 about about'
        ]);
    }

    //==========Admin updates place===============ERROR======
    public function test_updates_place_by_admin_ERROR()
    {
        //Create Admin
        $user = factory(User::class)->create(['isAdmin' => '1']);

        //add new place
        $response = $this->post('/api/placequeue?api_token='.$user->api_token , [
            'place_id' => '111',
            'title' => 'Place1',
            'about' => 'place1 about about',
            'lat' => '52.145648721987',
            'lng' => '21.245648456732',
            'type' => '333',
            'paid' => true,
        ]);

        $this->assertDatabaseHas('places', [
            'id' => '111',
            'title' => 'Place1',
            'about' => 'place1 about about'
        ]);

        //update place
        $response = $this->put('/admin/updateplace', [
            'place_id' => '111',
            'title' => 'Placeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee',
            'about' => '',
            'lat' => '',
            'lng' => '',
            'paid' => true,
        ]);

        $this->assertDatabaseHas('places', [
            'id' => '999',
            'title' => 'Place333',
            'about' => 'place333 about about',
        ]);

        $response->assertSessionHasErrors('title');
        $response->assertSessionHasErrors('about');
        $response->assertSessionHasErrors('lat');
        $response->assertSessionHasErrors('lng');
    }

    



    


}
