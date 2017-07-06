<?php

namespace Cron\Controller\Admin;

use Backend\Controller\BackendActionsTrait;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;

/**
 * Class CronController
 *
 * @package Cron\Controller
 */
class CronController extends Controller
{
    use BackendActionsTrait;

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
        'run'       => 'Cron.CronRun',
        'stats'     => 'Cron.CronStats'
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @throws \Cake\Core\Exception\Exception
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('Backend.Backend');
        $this->loadComponent('Backend.Action');
    }

    /**
     * @return Response|null
     */
    public function index()
    {
        return $this->Action->execute();
    }

    /**
     * @return Response|null
     */
    public function view()
    {
        return $this->Action->execute();
    }
}