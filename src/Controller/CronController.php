<?php
declare(strict_types=1);

namespace Cron\Controller;

use Cake\Controller\Controller;
use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Closure;
use Cron\Cron\CronManager;

/**
 * Class CronController
 *
 * @package Cron\Controller
 */
class CronController extends Controller
{
    /**
     * @var \Cron\Cron\CronManager
     */
    public CronManager $cronManager;

    /**
     * Initialize cron tasks and attach event listeners
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->cronManager = new CronManager(
            $this->getEventManager(),
            Configure::read('Cron.Manager', [])
        );

        $this->viewBuilder()->setClassName('Cron.Cron');
    }

    /**
     * {@inheritDoc}
     */
    public function beforeFilter(EventInterface $event)
    {
        //$this->_loadCronJobs();
    }

    /**
     * @return void
     */
    public function all()
    {
        $force = (bool)$this->request->getQuery('force');
        $config = $this->cronManager->getConfig();
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
    public function invokeAction(Closure $action, array $args): void
    {
        try {
            parent::invokeAction($action, $args);
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
