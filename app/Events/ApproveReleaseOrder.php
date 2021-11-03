<?php

namespace App\Events;

use App\Models\User;
use App\Models\ReleaseOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ApproveReleaseOrder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $approver;
    public $releaseOrder;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $approver, ReleaseOrder $releaseOrder)
    {
        $this->approver = $approver;
        $this->releaseOrder = $releaseOrder;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('admin-channel');
    }
}
