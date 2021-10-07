<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\UserRestoreEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
