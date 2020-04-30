<?php
declare(strict_types=1);

namespace Cron\Controller\Admin;

use Cake\Event\Event;
use Cron\Cron\CronManager;

/**
 * Class CronJobsController
 *
 * @package Cron\Controller\Admin
 */
class CronJobsController extends CronController
{
    /**
     * @var string
     */
    public $modelClass = "Cron.CronJobs";

    public $actions = [
        'index' => 'Admin.Index',
        'add' => 'Admin.Add',
        'view' => 'Admin.View',
        'edit' => 'Admin.Edit',
    ];

    /**
     * @var \Cron\Cron\CronManager
     */
    public $cronManager;

    /**
     * {@inheritDoc}
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Action->registerInline('run', ['scope' => ['table', 'form'], 'attrs' => [ 'data-icon' => 'car', 'target' => '_blank' ]]);
        //$this->Action->registerExternal(['controller' => 'CronJobResults', 'action' => 'index']);
    }

    /**
     * @return \Cron\Cron\CronManager
     */
    public function cronManager()
    {
        if (!$this->cronManager) {
            $this->cronManager = new CronManager();
        }

        return $this->cronManager;
    }

    /**
     * @return void
     */
    public function index()
    {
        $this->set('fields', ['name', 'interval', 'is_active', 'last_status', 'last_message', 'offset', 'modified']);
        $this->set('fields.whitelist', ['name', 'interval', 'is_active', 'last_status', 'last_message', 'offset', 'modified']);
        $this->Action->execute();
    }

    /**
     * @return void
     */
    public function add()
    {
        $this->set('fields.whitelist', ['name', 'class', 'desc', 'interval', 'is_active']);
        //$this->set('classes', $this->cronManager()->loadedTasks());
        $this->Action->execute();
    }

    /**
     * @return void
     */
    public function view($id = null)
    {
        $cronJob = $this->CronJobs->find()/*->contain('CronJobresults', function(Query $q) {
            $q->limit(30);
            return $q->orderDesc('CronJobresults.id');
        })*/->where(['CronJobs.id' => $id])->first();

        $this->set('entityOptions', ['contain' => []]);
        $this->set('entity', $cronJob);
        /*
        $this->set('related', ['CronJobresults' => [
            'fieldsBlacklist' => ['cron_job_id', 'log', 'client_ip', 'timestamp'],
            'rowActions' => [
                'view' => [__('View'), ['controller' => 'CronJobresults', 'action' => 'view', ':id']]
            ]
        ]]);
        */
        //$cronJobResults = $this->CronJobs->CronJobresults->find()->where(['cron_job_id' => $id])->limit(30)->all();
        //$this->set('cronJobResults', $cronJobResults);
        $this->set('tabs', [
           'results' => ['title' => 'Results', 'url' => ['controller' => 'CronJobresults', 'action' => 'index', 'qry' => ['cron_job_id' => $id]]],
        ]);

        $this->Action->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function run($id = null)
    {
        $job = $this->CronJobs->get($id, ['contain' => []]);

        return $this->redirect([
            'prefix' => false, 'plugin' => 'Cron', 'controller' => 'Cron', 'action' => $job->name,
        ]);
    }
}
