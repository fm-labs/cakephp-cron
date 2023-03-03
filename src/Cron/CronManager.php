<?php
declare(strict_types=1);

namespace Cron\Cron;

use Cake\Core\InstanceConfigTrait;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cron\Cron;
use Cron\Event\CronTaskEvent;

class CronManager implements EventDispatcherInterface
{
    use EventDispatcherTrait;
    use InstanceConfigTrait;

    /**
     * @var array Default config
     */
    protected array $_defaultConfig = [
        'notify_email' => '',
        'notify_on_error' => true,
    ];

    /**
     * @var \Cron\Cron\CronTaskRegistry
     */
    protected CronTaskRegistry $_registry;

    /**
     * @var array Tasks configs
     */
    protected $_tasks;

    /**
     * @param \Cake\Event\EventManager $eventManger
     * @param array $config
     */
    public function __construct(?EventManager $eventManger = null, array $config = [])
    {
        $this->setConfig($config);
        $this->setEventManager($eventManger);
        //$this->getEventManager()->on(new CronLoggingService());

        $this->_registry = new CronTaskRegistry();

        foreach(Cron::configured() as $taskName) {
            $this->loadTask($taskName, Cron::getConfig($taskName));
        }
    }

    public function __destruct()
    {
        //$this->getEventManager()->off($this);
    }

    public function loadTask($taskName, array $config = []): CronManager
    {
        // normalize config
        $config += ['className' => null, 'interval' => null, 'timestamp' => null];

        if (!$this->_registry->has($taskName)) {
            $this->_registry->load($taskName, $config);
        }

        $this->_tasks[$taskName] = $config;
        return $this;
    }

//    /**
//     * @return array List of loaded task names
//     */
//    public function loadedTasks(): array
//    {
//        return $this->_registry->loaded();
//    }

    /**
     * @param $taskName
     * @return boolean
     */
    public function hasTask($taskName): bool
    {
        return $this->_registry->has($taskName);
    }

    /**
     * Get task instance
     * @return \Cron\Cron\ICronTask
     */
    public function getTask($taskName): ICronTask
    {
        return $this->_registry->get($taskName);
    }

    /**
     * Execute all loaded tasks
     *
     * @param bool $force Set to TRUE to force execute (Defaults to FALSE)
     * @return array
     */
    public function executeAll(bool $force = false): array
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
     * @return \Cron\Cron\CronTaskResult
     */
    public function executeTask($taskName, bool $force = false): CronTaskResult
    {
        return $this->_execute($taskName, $this->_registry->get($taskName), $force);
    }

    /**
     * @param $taskName
     * @param \Cron\Cron\BaseCronTask $task
     * @param bool $force
     * @return \Cron\Cron\CronTaskResult
     */
    protected function _execute($taskName, BaseCronTask $task, bool $force = false): CronTaskResult
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

                    return new CronTaskResult(false, $msg);
                }
            }

            // dispatch beforeTask event
            // if event result is a CronTaskResult instance, the task won't be executed
            // the CronTaskResult instance will be returned instead
            $event = $this->getEventManager()->dispatch(new CronTaskEvent('Cron.beforeTask', $this, [
                'name' => $taskName,
                'config' => $config,
                'task' => $task
            ]));
            if ($event->getResult() instanceof CronTaskResult) {
                $result = $event->getResult();
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
        $event = $this->getEventManager()->dispatch(new CronTaskEvent('Cron.afterTask', $this, [
            'name' => $taskName,
            'config' => $config,
            'task' => $task,
            'result' => $result
        ]));
        if ($event->getResult() instanceof CronTaskResult) {
            $result = $event->getResult();
        }

        // cache last result
        //Cache::write($taskName, $result->toArray(), 'cron');

        return $result;
    }
}
