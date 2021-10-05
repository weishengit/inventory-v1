<?php

namespace App\Providers;

use App\Events\UserLoggedIn;
use App\Events\UserLoggedOutEvent;
use App\Listeners\LogUserLogin;
use App\Listeners\LogUserLogoutListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
