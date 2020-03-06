<?php

namespace Cron\Cron\Listener;

use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cron\Event\CronTaskEvent;

/**
 * Class CronTaskListener
 *
 * @package Cron\Event
 */
class CronTaskListener implements EventListenerInterface
{

    /**
     * @var int Default log level
     */
    static public $logLevel = LOG_DEBUG;

    /**
     * @var array Default log context
     */
    static public $logContext = ['cron'];

    /**
     * @return array List of implemented events
     */
    public function implementedEvents()
    {
        return [
            'Cron.beforeTask' => 'beforeTask',
            'Cron.afterTask' => 'afterTask',
        ];
    }

    /**
     * Log beforeTask event
     *
     * @param CronTaskEvent $event
     */
    public function beforeTask(CronTaskEvent $event)
    {
        //Log::write(static::$logLevel, sprintf("CronTask:%s EXECUTE", $event->getTaskName()), static::$logContext);
    }

    /**
     * Log afterTask event
     *
     * @param CronTaskEvent $event
     */
    public function afterTask(CronTaskEvent $event)
    {
        $result = $event->getResult();

        // database result logger
        try {
            $Jobs = TableRegistry::getTableLocator()->get('Cron.CronJobs');
            $job = $Jobs->find()->where(['name' => $event->getTaskName()])->firstOrFail();
            $job->last_status = $result->getStatus();
            $job->last_message = $result->getMessage();
            $job->last_executed = $result->getTimestamp();
            if (!$Jobs->save($job)) {
                throw new \RuntimeException("Failed to update CronJob");
            }

            $JobResults = TableRegistry::getTableLocator()->get('Cron.CronJobresults');
            $jobResult = $JobResults->newEntity([
                'cron_job_id' => $job->id,
                'status' => $result->getStatus(),
                'message' => $result->getMessage(),
                'timestamp' => $result->getTimestamp(),
                'log' => join("\n", $result->getLog())
            ]);
            if (!$JobResults->save($jobResult)) {
                throw new \RuntimeException("Failed to add CronJobresult");
            }
        } catch (\Exception $ex) {
            Log::error(sprintf(
                "[cron:task:%s] CronTaskListener: FAILED TO SAVE RESULTS: %s",
                $event->getTaskName(),
                $result->getMessage()
            ), static::$logContext);
        }

        // send error notifications
        $notifyOnError = $event->getCronManager()->config('notify_on_error');
        $notifyEmail = $event->getCronManager()->config('notify_email');
        if ($notifyOnError && $notifyEmail) {
            // send report mail
            if ($result->getStatus() == false) {
                try {
                    $email = new Email('admin');
                    $email->subject('Cronjob result notification for '. $event->getTaskName());
                    $email->template('Cron.cron_result_notify', false);
                    $email->viewVars([
                        'status' => $result->getStatus(),
                        'timestamp' => $result->getTimestamp(),
                        'message' => $result->getMessage(),
                        'log' => $result->getLog()
                    ]);
                    $email->send();
                } catch (\Exception $e) {
                    Log::error(sprintf(
                        "[cron:task:%s] CronTaskListener: FAILED TO SEND RESULT NOTIFY: %s",
                        $event->getTaskName(),
                        $result->getMessage()
                    ), static::$logContext);
                }
            }
        }

        if ($result->getStatus() == false) {
            Log::error(sprintf(
                "[cron:task:%s] CronTaskListener: FAILED %s",
                $event->getTaskName(),
                $result->getMessage()
            ), static::$logContext);
        } else {
            Log::write(static::$logLevel, sprintf(
                "[cron:task:%s] CronTaskListener: SUCCESS %s",
                $event->getTaskName(),
                $result->getMessage()
            ), static::$logContext);
        }
    }
}
