<?php

namespace App\Job;

use Psr\Log\LoggerInterface;
use WebFramework\Queue\Job;
use WebFramework\Queue\JobHandler;

/**
 * @implements JobHandler<Job>
 */
class SleepJobHandler implements JobHandler
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function handle(Job $job): void
    {
        if (!$job instanceof SleepJob)
        {
            return;
        }

        $min = $job->getMinSleepTime();
        $max = $job->getMaxSleepTime();
        $sleepTime = mt_rand($min, $max);

        $this->logger->debug('Starting sleep job', [
            'jobId' => $job->getJobId(),
            'minSleepTime' => $min,
            'maxSleepTime' => $max,
            'actualSleepTime' => $sleepTime,
            'failureChance' => $job->getFailureChance(),
        ]);

        usleep($sleepTime * 1000); // ms to microseconds

        // Check for failure
        if ($job->getFailureChance() > 0 && mt_rand(1, 100) <= $job->getFailureChance())
        {
            $this->logger->error('Sleep job failing randomly', [
                'jobId' => $job->getJobId(),
            ]);

            throw new \RuntimeException('Random failure occurred');
        }

        $this->logger->debug('Finished sleep job', [
            'jobId' => $job->getJobId(),
        ]);
    }
}
