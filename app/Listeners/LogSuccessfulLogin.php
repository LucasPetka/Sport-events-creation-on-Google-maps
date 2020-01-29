<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use Cookie;
use App\User;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $minutes = 720;
        $user = User::find($event->user->id);

        do{
            $token = Str::random(60);
            $user->api_token = $token;

         }while(User::where('api_token', $user->api_token)->exists());
         
        Cookie::queue(Cookie::make('api_token', $token, $minutes, null, null, false, false));
        $user->save();
    }

}
