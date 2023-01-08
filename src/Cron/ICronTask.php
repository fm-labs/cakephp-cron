<?php
declare(strict_types=1);

namespace Cron\Cron;


/**
 * CronTask interface
 *
 * @package Cron\Cron
 */
interface ICronTask
{
    /**
     * @return array|\Cron\Cron\CronTaskResult
     */
    function execute();
}
