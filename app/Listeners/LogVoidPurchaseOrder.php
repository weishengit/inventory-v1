<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\VoidPurchaseOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogVoidPurchaseOrder
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
     * @param  VoidPurchaseOrder  $event
     * @return void
     */
    public function handle(VoidPurchaseOrder $event)
    {
        Log::create([
            'user_id' => $event->approver->id,
            'type' => 'Purchase Order',
            'info' => $event->approver->name . 'voided PO#' . $event->purchaseOrder->po_num
        ]);
    }
}
