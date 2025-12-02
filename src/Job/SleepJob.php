<?php

namespace App\Job;

use WebFramework\Queue\Job;

class SleepJob implements Job
{
    private string $jobId;

    public function __construct(
        private int $minSleepTime,
        private int $maxSleepTime,
        private int $failureChance = 0 // 0-100
    ) {}

    public function getJobId(): string
    {
        return $this->jobId;
    }

    public function setJobId(string $jobId): void
    {
        $this->jobId = $jobId;
    }

    public function getJobName(): string
    {
        return 'SleepJob';
    }

    public function getMinSleepTime(): int
    {
        return $this->minSleepTime;
    }

    public function getMaxSleepTime(): int
    {
        return $this->maxSleepTime;
    }

    public function getFailureChance(): int
    {
        return $this->failureChance;
    }
}
