<?php
declare(strict_types=1);

namespace Cron\Event;

use Cake\Event\Event;
use Cron\Cron\CronManager;
use Cron\Cron\CronTaskResult;

/**
 * Class CronTaskEvent
 *
 * @package Cron\Event
 */
class CronTaskEvent extends Event
{
    /**
     * @return \Cron\Cron\CronManager
     */
    public function getSubject(): CronManager
    {
        return $this->_subject;
    }

    /**
     * @return string
     */
    public function getTaskName(): string
    {
        return $this->getData('name');
    }

    /**
     * @return \Cron\Cron\CronTaskResult
     */
    public function getResult(): ?CronTaskResult
    {
        return $this->getData('result');
    }
}
