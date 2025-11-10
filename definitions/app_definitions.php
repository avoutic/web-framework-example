<?php

/**
 * This file should contain your application specific PHP-DI definitions.
 */

namespace App;

use App\Event\PostCreated;
use App\Event\PostCreatedListener;
use DI;
use WebFramework\Event\EventService;

return [
    EventService::class => DI\decorate(function (EventService $eventService) {
        // Register PostCreated event with its listener
        $eventService->registerEvent(
            PostCreated::class,
            [
                PostCreatedListener::class,
            ]
        );

        return $eventService;
    }),
];
