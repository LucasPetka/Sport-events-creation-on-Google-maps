<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;

class LogSuccessfulLogout
{

    public $user;
    

    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {

    }
}
