<?php

namespace Cron\Controller\Admin;


use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;
use Cake\Event\Event;
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
    public $modelClass = "Cron.CronTasks";

    //public $components = ['RequestHandler'];

    /**
     * @var array List of configured tasks
     */
    public $tasks = [];

    /**
     * Initialize cron tasks and attach event listeners
     */
    public function initialize()
    {
        parent::initialize();

        //if (empty($this->tasks)) {
        //    $this->tasks = array_values(Configure::read('Cron.Tasks'));
        //}
    }

    public function beforeFilter(Event $event)
    {
        //$this->RequestHandler->respondAs('text');
    }

    public function index()
    {
        //$this->set('modelClass', false);
        //$this->set('data', $this->tasks);
        $this->set('_serialize', ['data']);

        $this->Backend->executeAction();
    }

}