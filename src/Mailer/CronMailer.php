<?php

namespace Cron\Mailer;

use Cake\Mailer\Mailer;
use Cron\Cron\CronTaskResult;

class CronMailer extends Mailer
{
    /**
     * @param string $taskName
     * @param CronTaskResult $result
     * @return CronMailer
     */
    public function cronResultNotify(string $taskName, CronTaskResult $result): CronMailer
    {
        $this
            ->setProfile('admin')
            ->setSubject('Cronjob result notification for ' . $taskName)
            ->setViewVars([
                'status' => $result->getStatus(),
                'timestamp' => $result->getTimestamp(),
                'message' => $result->getMessage(),
                //'log' => $result->getLog(),
            ])
            ->viewBuilder()
                ->setTemplate('Cron.cron_result_notify')
        ;
        return $this;
    }
}