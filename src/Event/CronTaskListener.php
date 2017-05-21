<?php

namespace Cron\Event;


use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cron\Cron\CronTaskResult;

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
            'Cron.Controller.beforeTask' => 'beforeTask',
            'Cron.Controller.afterTask' => 'afterTask',
        ];
    }

    /**
     * Log beforeTask event
     *
     * @param CronTaskEvent $event
     */
    public function beforeTask(CronTaskEvent $event)
    {
        Log::write(static::$logLevel, sprintf("CronTask:%s EXECUTE", $event->getTaskName()), static::$logContext);
    }

    /**
     * Log afterTask event
     *
     * @param CronTaskEvent $event
     */
    public function afterTask(CronTaskEvent $event)
    {
        $result = $event->getResult();
        if ($result->getStatus() === CronTaskResult::STATUS_FAIL) {
            Log::error(sprintf("CronTask:%s FAILED %s", $event->getTaskName(), $result->getMessage()));
            return;
        }

        Log::write(static::$logLevel, sprintf("CronTask:%s SUCCESS %s", $event->getTaskName(), $result->getMessage()), static::$logContext);
    }
}