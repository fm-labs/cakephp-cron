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

    public function run(?string $taskName) {

        $action = (string)$this->request->getParam('action');
        $force = (bool)$this->request->getQuery('force');

        if (!$this->cronManager->hasTask($taskName)) {
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
