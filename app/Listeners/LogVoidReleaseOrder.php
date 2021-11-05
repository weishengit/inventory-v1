<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\VoidReleaseOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogVoidReleaseOrder
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
     * @param  VoidReleaseOrder  $event
     * @return void
     */
    public function handle(VoidReleaseOrder $event)
    {
        Log::create([
            'user_id' => $event->approver->id,
            'type' => 'Release Order',
            'info' => $event->approver->name . ' voided RO#' . $event->releaseOrder->ro_num
        ]);
    }
}
