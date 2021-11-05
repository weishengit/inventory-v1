<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\ReleaseReleaseOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogReleaseReleaseOrder
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
     * @param  ReleaseReleaseOrder  $event
     * @return void
     */
    public function handle(ReleaseReleaseOrder $event)
    {
        Log::create([
            'user_id' => $event->approver->id,
            'type' => 'Release Order',
            'info' => $event->approver->name . ' released RO#' . $event->releaseOrder->ro_num
        ]);
    }
}
