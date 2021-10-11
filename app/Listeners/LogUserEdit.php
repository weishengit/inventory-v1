<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\UserEditEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log as FacadesLog;

class LogUserEdit
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
     * @param  UserEditEvent  $event
     * @return void
     */
    public function handle(UserEditEvent $event)
    {
        FacadesLog::channel('daily')
        ->info("User[".$event->previous['id']."] Edited By User[".$event->editor->id."]", [
            'editor' => $event->editor,
            'previous' => $event->previous,
            'new' => $event->new
        ]);

        Log::create([
            'user_id' => $event->new->id,
            'type' => 'Account',
            'info' => "Account has been editted by " . "[" . $event->editor->id . "]" . $event->editor->name
        ]);

        Log::create([
            'user_id' => $event->editor->id,
            'type' => 'Account',
            'info' => "Edited account " . "[" . $event->previous['id'] . "]" . $event->new->name
        ]);
    }
}
