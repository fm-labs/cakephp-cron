<?php
declare(strict_types=1);

namespace Cron\Cron\Logger;

use Cake\Event\EventListenerInterface;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cron\Event\CronTaskEvent;


/**
 * Class CronCsvLogger
 *
 * @package Cron\Service
 */
class CronCsvLogger implements EventListenerInterface
{
    /**
     * @return array List of implemented events
     */
    public function implementedEvents(): array
    {
        return [
            'Cron.afterTask' => 'afterTask',
        ];
    }

    /**
     * Log afterTask event
     *
     * @param \Cron\Event\CronTaskEvent $event
     */
    public function afterTask(CronTaskEvent $event)
    {
        $result = $event->getResult();
        $timestamp = $result->getTimestamp();
        $status = $result->getStatus();

        $statStr = sprintf("%s;%s;\"%s\"\n", date(DATE_ATOM), $status, $result->getMessage());

        $statsDir = TMP . "cron" . DS;
        $folder = new Folder($statsDir, true, 0777);

        $statsFile = $statsDir . $event->getData('name') . "_" . date("Y-m-d") . ".csv";
        $file = new File($statsFile, true);
        $file->append($statStr);
        $file->close();
    }
}
