<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\UserDeleteEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log as FacadesLog;

class LogDeleteUserListener
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
     * @param  UserDeleteEvent  $event
     * @return void
     */
    public function handle(UserDeleteEvent $event)
    {
        FacadesLog::channel('daily')
        ->info("User[".$event->user->id."] Disabled By User[".$event->admin->id."]", [
            'user' => $event->user,
            'admin' => $event->admin
        ]);

        Log::create([
            'user_id' => $event->user->id,
            'type' => 'Account',
            'info' => "Account has been deactivated by " . "[" . $event->admin->id . "]" . $event->admin->name
        ]);

        Log::create([
            'user_id' => $event->admin->id,
            'type' => 'Account',
            'info' => "Deactivated account " . "[" . $event->user->id . "]" . $event->user->name
        ]);
    }
}
