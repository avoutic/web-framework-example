<?php

namespace App\Event;

use Psr\Log\LoggerInterface;
use WebFramework\Event\Event;
use WebFramework\Event\EventListener;

/**
 * @implements EventListener<Event>
 */
class PostCreatedListener implements EventListener
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function handle(Event $event): bool
    {
        if (!$event instanceof PostCreated)
        {
            return false;
        }

        $post = $event->post;
        $userId = $event->post->getUserId();

        $this->logger->info('Post created (Event listener)', [
            'post_id' => $post->getId(),
            'post_title' => $post->getTitle(),
            'user_id' => $userId,
        ]);

        return true;
    }
}
