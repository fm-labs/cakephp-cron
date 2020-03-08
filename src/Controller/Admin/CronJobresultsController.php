<?php

namespace Cron\Controller\Admin;

use Cake\Core\Configure;
use Cake\Http\Response;

/**
 * Class CronJobresultsController
 *
 * @package Cron\Controller\Admin
 */
class CronJobresultsController extends CronController
{
    /**
     * @var string
     */
    public $modelClass = "Cron.CronJobresults";

    public $actions = [
        'index' => 'Backend.Index',
        'view' => 'Backend.View',
    ];

    /**
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['CronJobs'],
            'order' => ['CronJobresults.id' => 'DESC'],
        ];
        $this->set('fields', [
            'id',
            'cron_job' => ['formatter' => 'related', 'formatterArgs' => ['field' => 'name']],
            'status',
            'message',
            //'timestamp' => ['formatter' => 'timestamp'],
            'created',
        ]);
        $this->set('fields.blacklist', ['log', 'client_ip']);
        $this->Action->execute();
    }

    /**
     * @param int $id Model ID
     * @return void
     */
    public function view($id = null)
    {
        $cronJob = $this->CronJobresults->find()->contain(['CronJobs'])->where(['CronJobresults.id' => $id])->first();

        //$this->set('fields', ['log' => ['formatter' => 'nl2br']]);
        $this->set('entity', $cronJob);
        $this->set('related', ['CronJobs']);
        $this->Action->execute();
    }
}
