<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\TWCreateEvent;
use App\Events\TWInfoCreateEvent;
use App\Events\TWResubmitEvent;
use App\Events\TWUpdateEvent;
use App\Events\TWCloseEvent;

use App\Listeners\TWCreateEventListener;
use App\Listeners\TWInfoCreateEventListener;
use App\Listeners\TWResubmitEventListener;
use App\Listeners\TWUpdateEventListener;
use App\Listeners\TWCloseEventListener;

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
        TWCreateEvent::class => [
            TWCreateEventListener::class
        ],
        TWInfoCreateEvent::class => [
            TWInfoCreateEventListener::class
        ],
        TWResubmitEvent::class => [
            TWResubmitEventListener::class
        ],
        TWUpdateEvent::class => [
            TWUpdateEventListener::class
        ],
        TWCloseEvent::class => [
            TWCloseEventListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
