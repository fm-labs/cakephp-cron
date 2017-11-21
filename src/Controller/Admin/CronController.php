<?php

namespace Cron\Controller\Admin;

use Backend\Controller\BackendActionsTrait;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * Class CronController
 *
 * @package Cron\Controller
 */
class CronController extends Controller
{
    use BackendActionsTrait;

    /**
     * @var string
     */
    public $modelClass = "Cron.CronTasks";

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @throws \Cake\Core\Exception\Exception
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('Backend.Backend');
        //$this->loadComponent('Backend.Action');
    }

    /**
     * @return Response|null
     */
    public function index()
    {
        $tasks = $this->CronTasks->find()->all()->toArray();

        $this->set('tasks', $tasks);
        $this->set('_serialize', ['tasks']);
    }

    /**
     * {@inheritDoc}
     */
    public function run($taskId = null)
    {
        //$task = $this->CronTasks->get($taskId);
        return $this->redirect([
            'prefix' => false, 'plugin' => 'Cron', 'controller' => 'Cron', 'action' => $taskId
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function stats($taskId = null)
    {
        $task = $this->CronTasks->get($taskId);
        $date = $this->request->query('date');
        //@todo validate date input format
        $date = ($date) ?: date('Y-m-d');
        $filepath = TMP . 'cron' . DS . $task->id . '_' . $date . '.csv';

        try {
            $tableAlias = 'CronStatus' . Inflector::camelize($task->id);

            TableRegistry::config($tableAlias, [
                'className' => 'Cron.CronStats',
                'file' => $filepath,
                'columns' => ['timestamp', 'status', 'text'],
                'displayField' => 'task',
                'schema' => []
            ]);

            $CronStats = $this->loadModel($tableAlias);
            $stats = $CronStats->find()->all();
            $this->set(compact('stats'));

        } catch (\Exception $ex) {
            $this->Flash->error($ex->getMessage());
            return $this->redirect(['action' => 'index']);
        }

        $this->viewBuilder()->plugin('Cron');
        $this->viewBuilder()->templatePath('Admin/Cron');
        $this->render('stats');
    }
}