<?php

/**
 * This file should contain your application specific PHP-DI definitions.
 */

namespace App;

use App\Event\PostCreated;
use App\Event\PostCreatedListener;
use App\Job\SleepJob;
use App\Job\SleepJobHandler;
use DI;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\ProcessIdProcessor;
use Psr\Container\ContainerInterface;
use WebFramework\Event\EventService;
use WebFramework\Queue\DatabaseQueue;
use WebFramework\Queue\QueueService;
use WebFramework\Repository\QueueJobRepository;

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

    QueueService::class => DI\decorate(function (QueueService $queueService, ContainerInterface $c) {
        $queueJobRepository = $c->get(QueueJobRepository::class);
        $logger = $c->get('channels.default');

        $tasksQueue = new DatabaseQueue($logger, $queueJobRepository, 'tasks');
        $mailsQueue = new DatabaseQueue($logger, $queueJobRepository, 'mails');

        $queueService->register('tasks', $tasksQueue);
        $queueService->register('mails', $mailsQueue);

        $queueService->registerJobHandler(SleepJob::class, SleepJobHandler::class);

        return $queueService;
    }),
];
