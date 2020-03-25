<?php
declare(strict_types=1);

namespace Cron\Event;

use Cake\Event\Event;

/**
 * Class CronTaskEvent
 *
 * @package Cron\Event
 */
class CronTaskEvent extends Event
{
    /**
     * return CronManager
     */
    public function getCronManager()
    {
        return $this->_subject;
    }

    /**
     * @return string
     */
    public function getTaskName()
    {
        return $this->data['name'];
    }

    /**
     * @return \Cron\Cron\CronTaskResult
     */
    public function getResult()
    {
        return $this->data['result'];
    }
}
