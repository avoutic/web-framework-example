<?php

namespace App\Task;

use App\Repository\PostRepository;
use WebFramework\Task\ConsoleTask;

/**
 * Task for displaying post statistics.
 */
class PostsStatsTask extends ConsoleTask
{
    /**
     * PostsStatsTask constructor.
     *
     * @param PostRepository $postRepository The post repository
     * @param resource       $outputStream   The output stream
     */
    public function __construct(
        private PostRepository $postRepository,
        private $outputStream = STDOUT
    ) {}

    /**
     * Write a message to the output stream.
     *
     * @param string $message The message to write
     */
    private function write(string $message): void
    {
        fwrite($this->outputStream, $message);
    }

    public function getCommand(): string
    {
        return 'posts:stats';
    }

    public function getDescription(): string
    {
        return 'Display statistics about posts in the database';
    }

    public function getUsage(): string
    {
        return <<<'EOF'
        Display statistics about posts in the database.

        Usage:
        framework posts:stats

        This command shows:
        - Total number of posts
        - Number of posts per user
        - Most recent post date
        EOF;
    }

    /**
     * Execute the posts statistics task.
     */
    public function execute(): void
    {
        $allPosts = $this->postRepository->getAllPosts();
        $totalPosts = $allPosts->count();

        $this->write('Post Statistics'.PHP_EOL);
        $this->write('==============='.PHP_EOL.PHP_EOL);
        $this->write("Total Posts: {$totalPosts}".PHP_EOL.PHP_EOL);

        if ($totalPosts === 0)
        {
            $this->write('No posts found in the database.'.PHP_EOL);

            return;
        }

        // Count posts by user
        $postsByUser = [];
        $mostRecentPost = null;
        $mostRecentTime = 0;

        foreach ($allPosts as $post)
        {
            $userId = $post->getUserId();
            $postsByUser[$userId] = ($postsByUser[$userId] ?? 0) + 1;

            $createdAt = $post->getCreatedAt();
            if ($createdAt > $mostRecentTime)
            {
                $mostRecentTime = $createdAt;
                $mostRecentPost = $post;
            }
        }

        $this->write('Posts by User:'.PHP_EOL);
        foreach ($postsByUser as $userId => $count)
        {
            $this->write("  User ID {$userId}: {$count} post(s)".PHP_EOL);
        }

        if ($mostRecentPost !== null)
        {
            $this->write(PHP_EOL.'Most Recent Post:'.PHP_EOL);
            $this->write("  Title: {$mostRecentPost->getTitle()}".PHP_EOL);
            $this->write('  Created: '.date('Y-m-d H:i:s', $mostRecentPost->getCreatedAt()).PHP_EOL);
        }
    }

    public function handlesOwnBootstrapping(): bool
    {
        return false;
    }
}
