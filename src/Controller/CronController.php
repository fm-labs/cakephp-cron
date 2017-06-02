<?php

namespace Cron\Controller;


use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cron\Cron\CronTaskInterface;
use Cron\Cron\CronTaskRegistry;
use Cron\Cron\CronTaskResult;
use Cron\Event\CronStatsListener;
use Cron\Event\CronTaskEvent;
use Cron\Event\CronTaskListener;

/**
 * Class CronController
 *
 * @package Cron\Controller
 */
class CronController extends Controller
{
    public $modelClass = false;

    //public $components = ['RequestHandler'];

    /**
     * @var array List of configured tasks
     */
    public $tasks = [];

    /**
     * @var CronTaskRegistry
     */
    protected $_taskRegistry;

    /**
     * Initialize cron tasks and attach event listeners
     */
    public function initialize()
    {
        if (empty($this->tasks)) {
            $this->tasks = (array) Configure::read('Cron.Tasks');
        }

        // load tasks into registry
        $this->_taskRegistry = new CronTaskRegistry();
        foreach ($this->tasks as $taskName => $config) {
            // normalize config
            $config += ['className' => null, 'interval' => null];

            if (!$this->_taskRegistry->has($taskName)) {
                $this->_taskRegistry->load($taskName, $config);
            }

            $this->tasks[$taskName] = $config;
        }

        // use CronView
        $this->viewBuilder()->className('Cron.Cron');

        // attach cron task listener
        $this->eventManager()->on(new CronTaskListener());
        $this->eventManager()->on(new CronStatsListener());
    }

    public function beforeFilter(Event $event)
    {
        //$this->RequestHandler->respondAs('text');
    }

    public function index()
    {

        $force = (bool) $this->request->query('force');

        $results = [];
        foreach (array_keys($this->tasks) as $taskName) {

            $results[] = $this->_executeTask($taskName, $this->_taskRegistry->get($taskName), !$force);
        }

        $this->set(compact('results'));
        $this->set('_serialize', 'results');
    }

    /**
     * Check task.
     * Reads last task result from cache and checks execution interval
     *
     * @param $taskName
     * @return bool|CronTaskResult
     */
    protected function _checkTask($taskName)
    {
        $config = $this->tasks[$taskName];

        $lastResult = Cache::read($taskName, 'cron');
        if (!$lastResult) {
            return true;
        }

        // check interval
        if ($lastResult['timestamp'] + (int) $config['interval'] > time()) {
            $status = CronTaskResult::STATUS_NORUN;
            $nextRun = (new \DateTime())->setTimestamp($lastResult['timestamp'] + (int) $config['interval']);
            $nextRunStr = $nextRun->format("Y-m-d H:i:s");
            $msg = sprintf("Next run: %s", $nextRun->getTimestamp() - time());
            return new CronTaskResult($taskName, $status, $msg);
        }
    }

    /**
     * @param CronTaskInterface $task
     * @return CronTaskResult
     */
    protected function _executeTask($taskName, CronTaskInterface $task, $check = true)
    {
        $result = null;
        try {

            if ($check == true) {
                $checkResult = $this->_checkTask($taskName);
                if ($checkResult instanceof CronTaskResult) {
                    return $checkResult;
                }
            }

            // dispatch beforeTask event
            // if event result is a CronTaskResult instance, the task won't be executed
            // the CronTaskResult instance will be returned instead
            $event = $this->eventManager()->dispatch(new CronTaskEvent('Cron.Controller.beforeTask', $this, ['name' => $taskName, 'task' => $task]));
            if ($event->result instanceof CronTaskResult) {
                $result = $event->result;

            } else {

                $result = $task->execute();
                if (!($result instanceof CronTaskResult)) {
                    $result = new CronTaskResult($taskName, $result);
                }
            }

        } catch (\Exception $ex) {
            $result = new CronTaskResult($taskName, false, $ex->getMessage());

        } finally {

        }

        // dispatch afterTask event
        $event = $this->eventManager()->dispatch(new CronTaskEvent('Cron.Controller.afterTask', $this, ['name' => $taskName, 'result' => $result]));
        if ($event->result instanceof CronTaskResult) {
            $result = $event->result;
        }

        // cache last result
        Cache::write($taskName, $result->toArray(), 'cron');

        return $result;
    }

    public function invokeAction()
    {
        try {
            return parent::invokeAction();

        } catch (MissingActionException $ex) {

            $action = (string) $this->request->param('action');
            $force = (bool) $this->request->query('force');

            if (!$this->_taskRegistry->has($action)) {
                throw new MissingActionException([
                    'controller' => $this->name . "Controller",
                    'action' => $this->request->params['action'],
                    'prefix' => isset($this->request->params['prefix']) ? $this->request->params['prefix'] : '',
                    'plugin' => $this->request->params['plugin'],
                ]);
            }

            $task = $this->_taskRegistry->get($action);
            $result = $this->_executeTask($action, $task, !$force);

            $this->set('results', [$result]);
            $this->set('_serialize', 'results');
        }
    }
}