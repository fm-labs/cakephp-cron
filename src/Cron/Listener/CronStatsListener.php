<?php
declare(strict_types=1);

namespace Cron\Cron\Listener;

use Cake\Event\EventListenerInterface;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;

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
    public function implementedEvents(): array
    {
        return [
            'Cron.beforeTask' => 'beforeTask',
            'Cron.afterTask' => 'afterTask',
        ];
    }

    /**
     * Log beforeTask event
     *
     * @param \Cron\Cron\Listener\CronTaskEvent $event
     */
    public function beforeTask(CronTaskEvent $event)
    {
    }

    /**
     * Log afterTask event
     *
     * @param \Cron\Cron\Listener\CronTaskEvent $event
     */
    public function afterTask(CronTaskEvent $event)
    {
        $result = $event->getResult();
        $timestamp = $result->getTimestamp();
        $status = $result->getStatus();

        $statStr = sprintf("%s;%s;\"%s\"\n", $timestamp, $status, $result->getMessage());

        $statsDir = TMP . "cron" . DS;
        $folder = new Folder($statsDir, true, 0777);

        $statsFile = $statsDir . $event->getData('name') . "_" . date("Y-m-d") . ".csv";
        $file = new File($statsFile, true);
        $file->append($statStr);
        $file->close();
    }
}
