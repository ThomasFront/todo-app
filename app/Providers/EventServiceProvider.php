<?php

namespace App\Providers;

use App\Events\TaskCompleted;
use App\Listeners\SendTaskCompletedEmail;
use App\Listeners\SendTaskCompletedNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     */
    protected $listen = [
        TaskCompleted::class => [
            SendTaskCompletedEmail::class,
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
