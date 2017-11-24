<?php

namespace Cron\Controller\Admin;

use Backend\Controller\BackendActionsTrait;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Response;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cron\Cron\CronManager;

/**
 * Class CronJobsController
 *
 * @package Cron\Controller\Admin
 */
class CronJobsController extends AdminController
{
    use BackendActionsTrait;

    /**
     * @var string
     */
    public $modelClass = "Cron.CronJobs";

    public $actions = [
        'index' => 'Backend.Index',
        'add' => 'Backend.Add',
        'view' => 'Backend.View',
        'edit' => 'Backend.Edit'
    ];

    /**
     * @var CronManager
     */
    public $cronManager;

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Action->registerInline('run', ['scope' => ['table', 'form'], 'attrs' => [ 'data-icon' => 'car', 'target' => '_blank' ]]);
    }

    /**
     * @return CronManager
     */
    public function cronManager()
    {
        if (!$this->cronManager) {
            $this->cronManager = new CronManager();
        }
        return $this->cronManager;
    }

    /**
     * @return Response|null
     */
    public function index()
    {
        $this->set('fields', ['name', 'interval', 'is_active', 'last_status', 'last_message', 'offset', 'modified']);
        $this->set('fields.whitelist', ['name', 'interval', 'is_active', 'last_status', 'last_message', 'offset', 'modified']);
        $this->Action->execute();
    }

    /**
     * @return Response|null
     */
    public function add()
    {
        $this->set('fields.whitelist', ['name', 'class', 'desc', 'interval', 'is_active']);
        //$this->set('classes', $this->cronManager()->loadedTasks());
        $this->Action->execute();
    }

    public function view($id = null)
    {
        $cronJob = $this->CronJobs->find()->contain('CronJobresults', function(Query $q) {
            return $q->orderDesc('CronJobresults.id');
        })->where(['CronJobs.id' => $id])->first();

        $this->set('entity', $cronJob);
        $this->set('related', ['CronJobresults' => [
            'fieldsBlacklist' => ['cron_job_id', 'log', 'client_ip', 'timestamp'],
            'rowActions' => [
                'view' => [__('View'), ['controller' => 'CronJobresults', 'action' => 'view', ':id']]
            ]
        ]]);
        $this->Action->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function run($id = null)
    {
        $job = $this->CronJobs->get($id, ['contain' => []]);
        return $this->redirect([
            'prefix' => false, 'plugin' => 'Cron', 'controller' => 'Cron', 'action' => $job->name
        ]);
    }
}