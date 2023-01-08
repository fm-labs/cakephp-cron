<?php
declare(strict_types=1);

namespace Cron\Cron\Task;

use Cron\Cron\CronTaskResult;

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
        $time = new \DateTime();
        $this->log(sprintf("DebugCronTask was executed on %s", $time->format("Y-m-d H:i:s")), 'info');

        if (rand(0, 100) < 50) {
            return new CronTaskResult(true, "OK", null, $this->_log);
        } else {
            $this->log('Debug cron task failure triggered by random', 'critical');
            return new CronTaskResult(false, "FAILED", null, $this->_log);
        }
    }
}
