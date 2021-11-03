<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\CreateReleaseOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogCreateReleaseOrder
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
     * @param  CreateReleaseOrder  $event
     * @return void
     */
    public function handle(CreateReleaseOrder $event)
    {
        Log::create([
            'user_id' => $event->creator->id,
            'type' => 'Release Order',
            'info' => $event->creator->name . 'created RO#' . $event->releaseOrder->ro_num
        ]);
    }
}
