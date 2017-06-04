<?php

namespace Cron\Cron\Task;

use Cron\Cron\CronTask;


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

        return [true, "OK"];
    }
}