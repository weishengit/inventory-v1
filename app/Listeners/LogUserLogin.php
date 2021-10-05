<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\UserLoggedIn;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogUserLogin
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
     * @param  UserLoggedIn  $event
     * @return void
     */
    public function handle(UserLoggedIn $event)
    {
        Log::create([
            'user_id' => $event->user->id,
            'type' => 'Login',
            'info' => 'Logged In'
        ]);
    }
}
