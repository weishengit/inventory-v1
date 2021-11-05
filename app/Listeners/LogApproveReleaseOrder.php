<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\ApproveReleaseOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogApproveReleaseOrder
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
     * @param  ApproveReleaseOrder  $event
     * @return void
     */
    public function handle(ApproveReleaseOrder $event)
    {
        Log::create([
            'user_id' => $event->approver->id,
            'type' => 'Release Order',
            'info' => $event->approver->name . ' approved RO#' . $event->releaseOrder->ro_num
        ]);
    }
}
