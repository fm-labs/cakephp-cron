<?php

namespace Cron\Test\TestCase;

use Cron\Cron\CronTask;
use Cron\Cron\CronTaskResult;

/**
 * Class TestCronTask
 * @package Cron\Test\TestCase
 */
class TestCronTask extends CronTask
{

    /**
     * @return bool|CronTaskResult|null|mixed
     */
    public function execute()
    {
        return [true, "TEST OK"];
    }
}