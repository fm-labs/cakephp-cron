<?php

namespace Cron\Controller\Admin;

use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Response;
use Cron\Cron\CronTaskInterface;
use Cron\Cron\CronTaskRegistry;
use Cron\Cron\CronTaskResult;
use Cron\Event\CronTaskEvent;
use Cron\Event\CronTaskListener;
use App\Controller\Admin\AppController as AdminAppController;

/**
 * Class CronController
 *
 * @package Cron\Controller
 */
class CronController extends AdminAppController
{
    /**
     * @var string
     */
    public $modelClass = "Cron.CronTasks";

    /**
     * @var array
     */
    public $actions = [
        'index'     => 'Backend.Index',
        'view'      => 'Backend.View',
    ];

    /**
     * @return Response|null
     */
    public function index()
    {
        return $this->Backend->executeAction();
    }

    /**
     * @return Response|null
     */
    public function view()
    {
        return $this->Backend->executeAction();
    }
}