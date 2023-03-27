<?php
declare(strict_types=1);

namespace Cron\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\ServiceUnavailableException;
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
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $token = $this->getRequest()->getQuery('token');
        if ($token !== Configure::read('Cron.WebRunner.token')) {
            throw new BadRequestException("Invalid token");
        }

        if (!Configure::read('Cron.enabled')) {
            throw new ServiceUnavailableException("Cron service currently unavailable");
        }

    }

    /**
     * @return void
     */
    public function index(): void
    {
        $force = (bool)$this->request->getQuery('force');
        $config = $this->cronManager->getConfig();
        $results = $this->cronManager->executeAll($force);

        $this->set(compact('config', 'results'));
        $this->set('_serialize', 'results');
    }

    /**
     * @param string|null $taskName
     * @return void
     */
    public function run(?string $taskName): void
    {

        //$action = (string)$this->request->getParam('action');
        $force = (bool)$this->request->getQuery('force');

        if (!$this->cronManager->hasTask($taskName)) {
            throw new NotFoundException(sprintf("Task not found: %s", $taskName));
        }

        $result = $this->cronManager->executeTask($taskName, $force);

        $this->set('results', [$result]);
        $this->set('_serialize', 'results');
    }
}
