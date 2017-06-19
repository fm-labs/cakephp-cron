<?php

namespace Cron\Controller\Admin;


use Backend\Controller\BackendActionsTrait;
use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cron\Cron\CronTaskInterface;
use Cron\Cron\CronTaskRegistry;
use Cron\Cron\CronTaskResult;
use Cron\Event\CronTaskEvent;
use Cron\Event\CronTaskListener;
use App\Controller\Admin\AppController as AdminAppController;

/**
 * Class CronStatsController
 *
 * @package Cron\Controller
 */
class CronStatsController extends AdminAppController
{
    public $modelClass = "Cron.CronStats";

    public $actions = [
        'index'     => 'Backend.Index',
        'view'      => 'Backend.View',
    ];

    public function initialize()
    {
        parent::initialize();
        TableRegistry::config('Cron.CronStats', [
            'file' => TMP . 'cron' . DS . 'clear_cc_internal_2017-06-02.csv',
            'displayField' => 'task',
            'schema' => [
            ]
        ]);
    }

    public function index()
    {
        $this->Action->execute();
    }

    public function view()
    {
        $this->Action->execute();
    }
}