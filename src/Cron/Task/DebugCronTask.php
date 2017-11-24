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

        return new CronTaskResult(true, "OK");
    }
}