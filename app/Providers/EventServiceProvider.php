<?php

namespace App\Providers;

use App\Events\UserLoggedIn;
use App\Events\UserEditEvent;
use App\Listeners\LogUserEdit;
use App\Events\UserDeleteEvent;
use App\Listeners\LogUserLogin;
use App\Events\UserRestoreEvent;
use App\Events\VoidReleaseOrder;
use App\Events\CloseReleaseOrder;
use App\Events\SupplierEditEvent;
use App\Events\VoidPurchaseOrder;
use App\Events\ClosePurchaseOrder;
use App\Events\CreateReleaseOrder;
use App\Events\UserLoggedOutEvent;
use App\Events\ApproveReleaseOrder;
use App\Events\ReleaseReleaseOrder;
use App\Listeners\LogPurchaseOrder;
use App\Events\CreatedPurchaseOrder;
use App\Events\ReceivePurchaseOrder;
use App\Events\ApprovedPurchaseOrder;
use Illuminate\Support\Facades\Event;
use App\Listeners\LogVoidReleaseOrder;
use Illuminate\Auth\Events\Registered;
use App\Listeners\LogCloseReleaseOrder;
use App\Listeners\LogVoidPurchaseOrder;
use App\Listeners\LogClosePurchaseOrder;
use App\Listeners\LogCreateReleaseOrder;
use App\Listeners\LogDeleteUserListener;
use App\Listeners\LogUserLogoutListener;
use App\Listeners\LogApproveReleaseOrder;
use App\Listeners\LogReleaseReleaseOrder;
use App\Listeners\LogRestoreUserListener;
use App\Listeners\LogReceivePurchaseOrder;
use App\Listeners\LogSupplierEditListener;
use App\Listeners\LogApprovedPurchaseOrder;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserLoggedIn::class => [
            LogUserLogin::class,
        ],
        UserLoggedOutEvent::class => [
            LogUserLogoutListener::class
        ],
        UserEditEvent::class => [
            LogUserEdit::class
        ],
        UserDeleteEvent::class => [
            LogDeleteUserListener::class
        ],
        UserRestoreEvent::class => [
            LogRestoreUserListener::class
        ],
        SupplierEditEvent::class => [
            LogSupplierEditListener::class
        ],
        CreatedPurchaseOrder::class => [
            LogPurchaseOrder::class
        ],
        ApprovedPurchaseOrder::class => [
            LogApprovedPurchaseOrder::class
        ],
        ReceivePurchaseOrder::class => [
            LogReceivePurchaseOrder::class
        ],
        ClosePurchaseOrder::class => [
            LogClosePurchaseOrder::class
        ],
        VoidPurchaseOrder::class => [
            LogVoidPurchaseOrder::class
        ],
        CreateReleaseOrder::class => [
            LogCreateReleaseOrder::class
        ],
        ApproveReleaseOrder::class => [
            LogApproveReleaseOrder::class
        ],
        ReleaseReleaseOrder::class => [
            LogReleaseReleaseOrder::class
        ],
        CloseReleaseOrder::class => [
            LogCloseReleaseOrder::class
        ],
        VoidReleaseOrder::class => [
            LogVoidReleaseOrder::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
