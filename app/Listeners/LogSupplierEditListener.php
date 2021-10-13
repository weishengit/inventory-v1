<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\SupplierEditEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log as FacadesLog;

class LogSupplierEditListener
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
     * @param  SupplierEditEvent  $event
     * @return void
     */
    public function handle(SupplierEditEvent $event)
    {
        FacadesLog::channel('daily')
        ->info("Supplier[".$event->supplier->id."] Edited By User[".$event->editor->id."]", [
            'editor' => $event->editor,
            'supplier' => $event->supplier,
        ]);

        Log::create([
            'user_id' => $event->editor->id,
            'type' => 'Supplier',
            'info' => "Supplier[".$event->supplier->id."] has been editted by " . "[" . $event->editor->id . "]" . $event->editor->name
        ]);
    }
}
