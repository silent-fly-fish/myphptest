<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ExamineUserEvent' => [
            'App\Listeners\AddIntergralListener',
        ],
        'App\Events\AddUserUdidEvent' => [
            'App\Listeners\AddUdidListener',
        ],
        'App\Events\RegisterIMEvent' => [
            'App\Listeners\RegisterIMListener',
        ],
    ];
}
