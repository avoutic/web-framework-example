<?php

/**
 * This file should contain your application specific PHP-DI definitions.
 */

namespace App;

use App\Event\PostCreated;
use App\Event\PostCreatedListener;
use DI;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\ProcessIdProcessor;
use WebFramework\Event\EventService;

return [
    'channels.default' => function () {
        // Create a logger that writes to stdout
        $logger = new Logger('stdout');
        $logger->pushHandler(new StreamHandler('php://stdout', Level::Debug));
        $logger->pushProcessor(new ProcessIdProcessor());

        return $logger;
    },

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
