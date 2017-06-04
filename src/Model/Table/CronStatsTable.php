<?php

namespace Cron\Model\Table;

use Banana\Model\CsvTable;

class CronStatsTable extends CsvTable
{

    protected $_items = [];

    public function initialize()
    {
        parent::intialize();
    }
}