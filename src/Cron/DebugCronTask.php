<?php
declare(strict_types=1);

namespace Cron\Cron;

/**
 * Class DebugCronTask
 *
 * @package Cron\Cron\Task
 */
class DebugCronTask extends BaseCronTask
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $this->log("DebugCronTask executed", 'info');

        $result = new CronTaskResult(false, "Unknown");
        $result->appendLog("Starting debug cron task ...");
        if (rand(0, 100) < 50) {
            $result->setSuccess("OK");
        } else {
            $result->setFailed("Random failure");
        }
        $result->appendLog("Finished debug cron task!");
        return $result;
    }
}

