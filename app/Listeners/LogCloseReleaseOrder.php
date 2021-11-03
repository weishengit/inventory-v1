<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\CloseReleaseOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogCloseReleaseOrder
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
     * @param  CloseReleaseOrder  $event
     * @return void
     */
    public function handle(CloseReleaseOrder $event)
    {
        Log::create([
            'user_id' => $event->approver->id,
            'type' => 'Release Order',
            'info' => $event->approver->name . 'closed RO#' . $event->releaseOrder->ro_num
        ]);
    }
}
