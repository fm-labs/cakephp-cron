<?php

namespace Cron\Cron;

use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cron\Cron\Listener\CronTaskListener;
use Cron\Event\CronTaskEvent;

class CronManager implements EventDispatcherInterface
{
    use InstanceConfigTrait;
    use EventDispatcherTrait;

    /**
     * @var array Default config
     */
    protected $_defaultConfig = [
        'notify_email' => '',
        'notify_on_error' => true,
    ];

    /**
     * @var CronTaskRegistry
     */
    protected $_registry;

    /**
     * @var array Tasks configs
     */
    protected $_tasks;

    /**
     * @param EventManager $eventManger
     * @param array $config
     */
    public function __construct(EventManager $eventManger = null, $config = [])
    {
        $this->config($config);
        $this->eventManager($eventManger);
        $this->eventManager()->on(new CronTaskListener());

        $this->_registry = new CronTaskRegistry();
    }

    public function __destruct()
    {
        $this->eventManager()->off($this);
    }

    public function loadTask($taskName, array $config = [])
    {
        // normalize config
        $config += ['className' => null, 'interval' => null, 'timestamp' => null];

        if (!$this->_registry->has($taskName)) {
            $this->_registry->load($taskName, $config);
        }

        $this->_tasks[$taskName] = $config;

        return $this;
    }

    /**
     * @return array List of loaded task names
     */
    public function loadedTasks()
    {
        return $this->_registry->loaded();
    }

    /**
     * Has task instance
     * @return CronTask
     */
    public function hasTask($taskName)
    {
        return $this->_registry->has($taskName);
    }

    /**
     * Get task instance
     * @return CronTask
     */
    public function getTask($taskName)
    {
        return $this->_registry->get($taskName);
    }

    /**
     * Execute all loaded tasks
     *
     * @param bool $force Set to TRUE to force execute (Defaults to FALSE)
     * @return array
     */
    public function executeAll($force = false)
    {
        $results = [];
        foreach ($this->_registry->loaded() as $taskName) {
            $results[$taskName] = $this->_execute($taskName, $this->_registry->get($taskName), $force);
        }

        return $results;
    }

    /**
     * Execute task by name
     *
     * @param $taskName
     * @param bool $force Set to TRUE to force execute (Defaults to FALSE)
     * @return CronTaskResult
     */
    public function executeTask($taskName, $force = false)
    {
        return $this->_execute($taskName, $this->_registry->get($taskName), $force);
    }

    /**
     * @param $taskName
     * @param CronTask $task
     * @param bool $force
     * @return CronTaskResult
     */
    protected function _execute($taskName, CronTask $task, $force = false)
    {
        $config = $this->_tasks[$taskName];
        $result = null;
        try {
            if (!$force) {
                $lastExecuted = $config['timestamp'];
                if ($lastExecuted && $lastExecuted + $config['interval'] > time()) {
                    $status = null;
                    $nextRun = (new \DateTime())->setTimestamp($lastExecuted + $config['interval']);
                    //$nextRunStr = $nextRun->format("Y-m-d H:i:s");
                    $msg = sprintf("%ds", $nextRun->getTimestamp() - time());

                    return new CronTaskResult(-1, $msg);
                }
            }

            // dispatch beforeTask event
            // if event result is a CronTaskResult instance, the task won't be executed
            // the CronTaskResult instance will be returned instead
            $event = $this->eventManager()->dispatch(new CronTaskEvent('Cron.beforeTask', $this, ['name' => $taskName, 'config' => $config, 'task' => $task]));
            if ($event->result instanceof CronTaskResult) {
                $result = $event->result;
            } else {
                $result = $task->execute();
                if (!($result instanceof CronTaskResult)) {
                    throw new \Exception('CRON_BAD_TASK_RESULT');
                }
            }
        } catch (\Exception $ex) {
            $result = new CronTaskResult(false, $ex->getMessage());
        }

        // dispatch afterTask event
        $event = $this->eventManager()->dispatch(new CronTaskEvent('Cron.afterTask', $this, ['name' => $taskName, 'config' => $config, 'result' => $result]));
        if ($event->result instanceof CronTaskResult) {
            $result = $event->result;
        }

        // cache last result
        //Cache::write($taskName, $result->toArray(), 'cron');

        return $result;
    }
}
