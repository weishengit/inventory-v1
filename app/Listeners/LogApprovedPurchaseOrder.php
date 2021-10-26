<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\ApprovedPurchaseOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Queue\InteractsWithQueue;

class LogApprovedPurchaseOrder
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
     * @param  ApprovedPurchaseOrder  $event
     * @return void
     */
    public function handle(ApprovedPurchaseOrder $event)
    {
        FacadesLog::channel('daily')->info('User Login', [$event->approver]);

        Log::create([
            'user_id' => $event->approver->id,
            'type' => 'Purchase Order',
            'info' => $event->approver->name . 'approved PO#' . $event->purchaseOrder->po_num
        ]);
    }
}
