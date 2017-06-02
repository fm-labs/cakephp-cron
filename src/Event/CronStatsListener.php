<?php

namespace Cron\Event;


use Cake\Event\EventListenerInterface;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Log\Log;
use Cron\Cron\CronTaskResult;

/**
 * Class CronStatsListener
 *
 * @package Cron\Event
 */
class CronStatsListener implements EventListenerInterface
{

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
    }

    /**
     * Log afterTask event
     *
     * @param CronTaskEvent $event
     */
    public function afterTask(CronTaskEvent $event)
    {
        $result = $event->getResult();
        $timestamp = $result->getTimestamp();
        $status = $result->getStatus();

        $statStr = sprintf("%s;%s;\"%s\"\n", $timestamp, $status, $result->getMessage());

        $statsDir = TMP . "cron" . DS;
        $folder = new Folder($statsDir, true, 0777);

        $statsFile = $statsDir . $result->getTaskName() . "_" . date("Y-m-d") . ".csv";
        $file = new File($statsFile, true);
        $file->append($statStr);
        $file->close();
    }
}