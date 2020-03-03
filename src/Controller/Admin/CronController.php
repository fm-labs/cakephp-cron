<?php

namespace Cron\Controller\Admin;

use Cake\Controller\Controller;

/**
 * Class AdminController
 *
 * @package Cron\Controller
 * @property \Backend\Controller\Component\ActionComponent $Action
 * @property \Backend\Controller\Component\ActionComponent $Backend
 */
class CronController extends Controller
{
    /**
     * Index Action
     *
     * @return \Cake\Network\Response
     */
    public function index()
    {
        return $this->redirect(['controller' => 'CronJobs', 'action' => 'index']);
    }
}
