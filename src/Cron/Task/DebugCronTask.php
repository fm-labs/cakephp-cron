<?php

namespace Cron\Cron\Task;


use Cake\Log\Log;
use Cron\Cron\CronTaskInterface;

class DebugCronTask extends CronTask
{
    public function execute()
    {
        $time = new \DateTime();
        $this->log(sprintf("DebugCronTask was executed on %s", $time->format("Y-m-d H:i:s")), LOG_DEBUG);

        return [true, "OK"];
    }
}