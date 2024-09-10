<?php

namespace App\Providers;

use App\Events\LeadInterestedAfterFirstVisit;
use App\Events\SubscriptionCreated;
use App\Listeners\CreateCallsForSubscription;
use App\Listeners\CreateNextCallForLead;
use App\Listeners\UpdateClientStatusAndDeleteCalls;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SubscriptionCreated::class => [
            UpdateClientStatusAndDeleteCalls::class,
            CreateCallsForSubscription::class,
        ],
        LeadInterestedAfterFirstVisit::class => [
            CreateNextCallForLead::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
