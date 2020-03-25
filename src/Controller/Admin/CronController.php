<?php
declare(strict_types=1);

namespace Cron\Controller\Admin;

/**
 * Class AdminController
 *
 * @package Cron\Controller
 * @property \Backend\Controller\Component\ActionComponent $Action
 * @property \Backend\Controller\Component\ActionComponent $Backend
 */
class CronController extends \Backend\Controller\Controller
{
    /**
     * Index Action
     *
     * @return \Cake\Http\Response
     */
    public function index()
    {
        return $this->redirect(['controller' => 'CronJobs', 'action' => 'index']);
    }
}
