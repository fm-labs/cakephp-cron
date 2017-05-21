<?php

namespace Cron\Event;


use Cake\Event\Event;
use Cron\Cron\CronTaskResult;

/**
 * Class CronTaskEvent
 *
 * @package Cron\Event
 */
class CronTaskEvent extends Event
{
    /**
     * @return string
     */
    public function getTaskName()
    {
        return $this->data['name'];
    }

    /**
     * @return CronTaskResult
     */
    public function getResult()
    {
        return $this->data['result'];
    }

}