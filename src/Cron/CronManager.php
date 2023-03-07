<?php
declare(strict_types=1);

namespace Cron\Cron;

use Cake\Cache\Cache;
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
     * @param EventManager|null $eventManager
     * @param array $config
     */
    public function __construct(?EventManager $eventManager = null, array $config = [])
    {
        $this->setConfig($config);
        $this->setEventManager($eventManager);
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
        $config += ['className' => null, 'interval' => null];

        if (!$this->_registry->has($taskName)) {
            $this->_registry->load($taskName, $config);
        }
        return $this;
    }

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
     * @param string $taskName
     * @return ICronTask
     */
    public function getTask(string $taskName): ICronTask
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
     * @param string $taskName
     * @param ICronTask $task
     * @param bool $force
     * @return CronTaskResult
     */
    protected function _execute(string $taskName, ICronTask $task, bool $force = false): CronTaskResult
    {
        try {
            $config = Cron::getConfig($taskName);

            if (!$force) {
                $lastResult = Cache::read($taskName, 'cron');
                $lastExecuted = $lastResult['timestamp'];
                if ($lastExecuted && $lastExecuted + $config['interval'] > time()) {
                    $nextRun = (new \DateTime())->setTimestamp($lastExecuted + $config['interval']);
                    $msg = sprintf("Wait %ds", $nextRun->getTimestamp() - time());
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

                // if the task does not return anything, and does not throw an exception,
                // it is assumed the task execution was successful
                if (!$result) {
                    $result = new CronTaskResult(true, "OK");
                }
            }

            if (!($result instanceof CronTaskResult)) {
                throw new \LogicException("Malformed result. CronTaskResult instance expected");
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
        Cache::write($taskName, $result->toArray(), 'cron');

        return $result;
    }
}
