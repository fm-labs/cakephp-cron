<?php

namespace Cron\Mailer;

use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Cron\Cron\CronTaskResult;
use Cron\Event\CronTaskEvent;

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
            ->setProfile(Configure::read('Cron.emailProfile', 'admin'))
            ->setSubject('Cronjob result notification for ' . $taskName)
            ->setViewVars([
                'status' => $result->getStatus(),
                'timestamp' => $result->getTimestamp(),
                'message' => $result->getMessage(),
                'log' => $result->getLog(),
            ])
            ->viewBuilder()
                ->setTemplate('Cron.cron_result_notify')
        ;
        return $this;
    }

    public function onAfterTask(CronTaskEvent $event)
    {
        $taskName = $event->getTaskName();
        $result = $event->getResult();

        if (!$result->isSuccess()) {
            // send error notification
            $this->send('cronResultNotify', [$taskName, $result]);
        }

    }

    public function implementedEvents(): array
    {
        return [
            'Cron.afterTask' => 'onAfterTask'
        ];
    }
}