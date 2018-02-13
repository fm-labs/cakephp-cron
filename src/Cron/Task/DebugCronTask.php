<?php

namespace Cron\Cron\Task;

use Cron\Cron\CronTask;
use Cron\Cron\CronTaskResult;


/**
 * Class DebugCronTask
 *
 * @package Cron\Cron\Task
 */
class DebugCronTask extends CronTask
{
    /**
     * @return array
     */
    public function execute()
    {
        $time = new \DateTime();
        $this->log(sprintf("DebugCronTask was executed on %s", $time->format("Y-m-d H:i:s")), LOG_DEBUG);

        if (rand(0,100) < 1) {
            return new CronTaskResult(true, "OK");
        } else {
            return new CronTaskResult(false, "FAILED", null, ['Debug cron task failure triggered by random']);
        }
    }
}