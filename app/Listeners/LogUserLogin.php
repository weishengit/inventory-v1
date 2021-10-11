<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\UserLoggedIn;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log as FacadesLog;

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
        FacadesLog::channel('daily')->info('User Login', [$event->user]);

        Log::create([
            'user_id' => $event->user->id,
            'type' => 'Login',
            'info' => 'Logged In'
        ]);
    }
}
