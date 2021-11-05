<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\ReceivePurchaseOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogReceivePurchaseOrder
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
     * @param  ReceivePurchaseOrder  $event
     * @return void
     */
    public function handle(ReceivePurchaseOrder $event)
    {
        Log::create([
            'user_id' => $event->approver->id,
            'type' => 'Purchase Order',
            'info' => $event->approver->name . ' received PO#' . $event->purchaseOrder->po_num
        ]);
    }
}
