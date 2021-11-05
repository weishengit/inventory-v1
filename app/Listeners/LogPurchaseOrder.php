<?php

namespace App\Listeners;

use App\Models\Log;
use App\Events\CreatedPurchaseOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log as FacadesLog;


class LogPurchaseOrder
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
     * @param  CreatedPurchaseOrder  $event
     * @return void
     */
    public function handle(CreatedPurchaseOrder $event)
    {
        Log::create([
            'user_id' => $event->creator->id,
            'type' => 'Purchase Order',
            'info' => $event->creator->name . ' created PO#' . $event->purchaseOrder->po_num
        ]);
    }
}
