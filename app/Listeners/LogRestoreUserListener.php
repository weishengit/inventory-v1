<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\UserRestoreEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log as FacadesLog;

class LogRestoreUserListener
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
     * @param  UserRestoreEvent  $event
     * @return void
     */
    public function handle(UserRestoreEvent $event)
    {

        FacadesLog::channel('daily')
        ->info("User[".$event->user->id."] Restored By User[".$event->admin->id."]", [
            'user' => $event->user,
            'admin' => $event->admin
        ]);

        Log::create([
            'user_id' => $event->user->id,
            'type' => 'Account',
            'info' => "Account has been restored by " . "[" . $event->admin->id . "]" . $event->admin->name
        ]);

        Log::create([
            'user_id' => $event->admin->id,
            'type' => 'Account',
            'info' => "Restored account " . "[" . $event->user->id . "]" . $event->user->name
        ]);
    }
}
