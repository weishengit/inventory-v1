<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\ClosePurchaseOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogClosePurchaseOrder
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
     * @param  ClosePurchaseOrder  $event
     * @return void
     */
    public function handle(ClosePurchaseOrder $event)
    {
        Log::create([
            'user_id' => $event->approver->id,
            'type' => 'Purchase Order',
            'info' => $event->approver->name . ' closed PO#' . $event->purchaseOrder->po_num
        ]);
    }
}
