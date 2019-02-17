<?php

namespace Cron\Controller\Admin;

use Backend\Controller\BackendActionsTrait;
use Cake\Core\Configure;
use Cake\Network\Response;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cron\Cron\CronManager;

/**
 * Class CronJobresultsController
 *
 * @package Cron\Controller\Admin
 */
class CronJobresultsController extends CronController
{
    use BackendActionsTrait;

    /**
     * @var string
     */
    public $modelClass = "Cron.CronJobresults";

    public $actions = [
        'index' => 'Backend.Index',
        'view' => 'Backend.View',
    ];

    /**
     * @return Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['CronJobs'],
            'order' => ['CronJobresults.id' => 'DESC']
        ];
        $this->set('fields', [
            'id',
            'cron_job' => ['formatter' => 'related', 'formatterArgs' => ['field' => 'name']],
            'status',
            'message',
            //'timestamp' => ['formatter' => 'timestamp'],
            'created'
        ]);
        $this->set('fields.blacklist', ['log', 'client_ip']);
        $this->Action->execute();
    }

    public function view($id = null)
    {
        $cronJob = $this->CronJobresults->find()->contain(['CronJobs'])->where(['CronJobresults.id' => $id])->first();

        //$this->set('fields', ['log' => ['formatter' => 'nl2br']]);
        $this->set('entity', $cronJob);
        $this->set('related', ['CronJobs']);
        $this->Action->execute();
    }
}
