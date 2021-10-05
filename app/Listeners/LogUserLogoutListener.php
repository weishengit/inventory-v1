<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\UserLoggedOutEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogUserLogoutListener
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
     * @param  UserLoggedOutEvent  $event
     * @return void
     */
    public function handle(UserLoggedOutEvent $event)
    {
        Log::create([
            'user_id' => $event->user->id,
            'type' => 'Login',
            'info' => 'Logged Out'
        ]);
    }
}
