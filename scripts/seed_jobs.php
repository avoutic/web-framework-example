#!/usr/bin/env php
<?php

use App\Job\SleepJob;
use WebFramework\Queue\QueueService;
use WebFramework\Task\TaskRunner;

require_once __DIR__.'/../vendor/autoload.php';

$taskRunner = new TaskRunner(__DIR__.'/..');
$taskRunner->build();

/** @var QueueService $queueService */
$queueService = $taskRunner->get(QueueService::class);

$count = $argv[1] ?? 1;
$minSleepTime = $argv[2] ?? 1000;
$maxSleepTime = $argv[3] ?? 1000;
$failureChance = $argv[4] ?? 0;
$queueName = $argv[5] ?? 'tasks';

echo "Dispatching {$count} jobs to '{$queueName}'...\n";
echo "Sleep time: {$minSleepTime} - {$maxSleepTime} ms\n";
echo "Failure chance: {$failureChance}%\n";

for ($i = 0; $i < $count; $i++)
{
    $job = new SleepJob((int) $minSleepTime, (int) $maxSleepTime, (int) $failureChance);
    $queueService->dispatch($job, $queueName);
    echo '.';
}

echo "\nDone.\n";
