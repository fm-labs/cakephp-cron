<?php

namespace Cron\Model\Table;

use Banana\Model\ArrayTable;
use Cake\Core\Configure;

/**
 * Class CronTasksTable
 *
 * @package Cron\Model\Table
 */
class CronTasksTable extends ArrayTable
{
    /**
     * @var array
     */
    protected $_items = [];

    /**
     * Custom initialize method
     */
    public function initialize()
    {
        $this->displayField('className');
    }

    /**
     * Return array table data
     *
     * @return array
     */
    public function getItems()
    {
        if (empty($this->_items)) {

            foreach (Configure::read('Cron.Tasks') as $id => $config) {
                $task = array_merge(['id' => $id], $config);
                $this->_items[] = $task;
            }
        }
        return $this->_items;
    }
}