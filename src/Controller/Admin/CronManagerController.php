<?php
declare(strict_types=1);

namespace Cron\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cron\Cron;
use Cron\Cron\CronManager;

/**
 * Class CronManagerController
 *
 * @package Cron\Controller\Admin
 */
class CronManagerController extends CronController
{
    public array $actions = [];

    /**
     * @var \Cake\Datasource\RepositoryInterface|CronManager|null
     */
    private $cronManager;

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->cronManager = new CronManager(
            $this->getEventManager(),
            Configure::read('Cron.Manager', [])
        );
    }

    /**
     * @return void
     */
    public function index()
    {
        $cronTasks = [];
        foreach (Cron::configured() as $taskName) {
            $cronTasks[$taskName] = Cron::getConfig($taskName);
            $cronTasks[$taskName]['_last'] = CronManager::getLastResult($taskName);
        }
        $this->set(compact('cronTasks'));
        $this->render('index');
    }

    /**
     * @return void|\Cake\Http\Response
     */
    public function view(string $taskName) {

        $config = Cron::getConfig($taskName);
        if (!$config) {
            $this->Flash->error("Cron config not found: " . $taskName);
            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('taskName', 'config'));
        $this->render('run');
    }

    /**
     * @return void|\Cake\Http\Response
     */
    public function run(string $taskName) {

        $config = Cron::getConfig($taskName);
        if (!$config) {
            $this->Flash->error("Cron config not found: " . $taskName);
            return $this->redirect(['action' => 'index']);
        }

        $force = (bool)$this->getRequest()->getQuery('force');
        $result = $this->cronManager->executeTask($taskName, $force);
        if ($result->isSuccess()) {
            $this->Flash->success($result->getMessage());
        } else {
            $this->Flash->error($result->getMessage());
        }

        $this->set(compact('taskName', 'config', 'result'));
    }
}
