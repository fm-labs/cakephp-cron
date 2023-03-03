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
     * Execute the cron task logic.
     * If the return type is void, it is assumed the task was successful.
     * If an exception is thrown, it is assumed the task failed.
     * If a CronTaskResult instance is returned, the success status is determined from the CronTaskResult state.
     *
     * @return void|\Cron\Cron\CronTaskResult
     * @throws \Exception
     */
    function execute();
}
