<?php
declare(strict_types=1);

namespace Cron\Controller\Admin;

use Cake\Event\EventInterface;
use Cron\Cron;

/**
 * Class CronManagerController
 *
 * @package Cron\Controller\Admin
 */
class CronManagerController extends CronController
{

    /**
     * {@inheritDoc}
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * @return void
     */
    public function index()
    {
        $cronConfigs = [];
        foreach (Cron::configured() as $configName) {
            $cronConfigs[$configName] = Cron::getConfig($configName);
        }
        $this->set(compact('cronConfigs'));
    }

    /**
     * @return void|\Cake\Http\Response
     */
    public function view($configName) {

        $config = Cron::getConfig($configName);
        if (!$config) {
            $this->Flash->error("Cron config not found: " . $configName);
            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('configName', 'config'));
        $this->render('run');
    }

    /**
     * @return void|\Cake\Http\Response
     */
    public function run($configName) {

        $config = Cron::getConfig($configName);
        if (!$config) {
            $this->Flash->error("Cron config not found: " . $configName);
            return $this->redirect(['action' => 'index']);
        }

        $result = Cron::run($configName);
        if (!$result) {
            $this->Flash->error("Cron execution failed: " . $configName);
            return $this->redirect(['action' => 'index']);
        }

        if ($result->isSuccess()) {
            $this->Flash->success($result->getMessage());
        } else {
            $this->Flash->error($result->getMessage());
        }

        $this->set(compact('configName', 'config', 'result'));
    }
}
