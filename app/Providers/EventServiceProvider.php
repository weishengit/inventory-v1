<?php

namespace App\Providers;

use App\Events\SupplierEditEvent;
use App\Events\UserLoggedIn;
use App\Events\UserEditEvent;
use App\Listeners\LogUserEdit;
use App\Events\UserDeleteEvent;
use App\Listeners\LogUserLogin;
use App\Events\UserRestoreEvent;
use App\Events\UserLoggedOutEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\LogDeleteUserListener;
use App\Listeners\LogUserLogoutListener;
use App\Listeners\LogRestoreUserListener;
use App\Listeners\LogSupplierEditListener;
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
