<?php
declare(strict_types=1);

namespace Cron\Controller;

use Cake\Controller\Controller;
use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Log\Log;
use Cron\Cron\CronManager;

/**
 * Class CronController
 *
 * @package Cron\Controller
 */
class CronController extends Controller
{
    public $modelClass = "Cron.CronJobs";

    /**
     * @var \Cron\Cron\CronManager
     */
    public $cronManager;

    /**
     * Initialize cron tasks and attach event listeners
     *
     * @return void
     */
    public function initialize()
    {
        // use CronView
        $this->viewBuilder()->setClassName('Cron.Cron');

        $this->cronManager = new CronManager($this->getEventManager(), Configure::read('Cron.CronManager'));
    }

    /**
     * {@inheritDoc}
     */
    public function beforeFilter(Event $event)
    {
        $this->_loadCronJobs();
    }

    /**
     * @return void
     */
    public function index()
    {
        $config = $this->cronManager->getConfig();
        $force = (bool)$this->request->getQuery('force');
        $results = $this->cronManager->executeAll($force);

        $this->set(compact('config', 'results'));
        $this->set('_serialize', 'results');
    }

    /**
     * Invoke cron task by action
     *
     * @return mixed
     * @TODO Refactor this hack-ish code
     */
    public function invokeAction()
    {
        try {
            return parent::invokeAction();
        } catch (MissingActionException $ex) {
            $action = (string)$this->request->getParam('action');
            $force = (bool)$this->request->getQuery('force');

            if (!$this->cronManager->hasTask($action)) {
                throw new MissingActionException([
                    'controller' => $this->name . "Controller",
                    'action' => $this->request->getParam('action'),
                    'prefix' => $this->request->getParam('prefix') ?: '',
                    'plugin' => $this->request->getParam('plugin'),
                ]);
            }

            $result = $this->cronManager->executeTask($action, $force);

            $this->set('results', [$result]);
            $this->set('_serialize', 'results');
        }
    }

    /**
     * @return void
     */
    protected function _loadCronJobs()
    {
        $cronJobs = [];
        try {
            $cronJobs = $this->CronJobs->find()->where(['is_active' => true])->all();
        } catch (\Exception $ex) {
            Log::error('[cron] LoadTasks: ' . $ex->getMessage(), ['cron']);
        }

        $tasks = [];
        foreach ($cronJobs as $cronJob) {
            $taskName = $cronJob->name;
            $config = [
                'className' => $cronJob->class,
                'interval' => $cronJob->interval,
                'timestamp' => $cronJob->last_executed,
                //'active' => $cronJob->is_active
            ];

            try {
                $this->cronManager->loadTask($taskName, $config);
            } catch (\Exception $ex) {
                Log::error(sprintf('CronController: Failed to load task %s: %s', $taskName, $ex->getMessage()), ['cron']);
            }
        }
    }
}
