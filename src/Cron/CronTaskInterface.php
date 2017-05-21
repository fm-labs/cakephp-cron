<?php

namespace Cron\Cron;

/**
 * Interface CronTaskInterface
 *
 * Common interface for cron tasks
 *
 * @package Cron\Cron
 */
interface CronTaskInterface
{
    /**
     * @return bool|CronTaskResult|null|mixed
     */
    public function execute();
}