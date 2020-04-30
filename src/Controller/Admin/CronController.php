<?php
declare(strict_types=1);

namespace Cron\Controller\Admin;

/**
 * Class AdminController
 *
 * @package Cron\Controller
 * @property \Admin\Controller\Component\ActionComponent $Action
 * @property \Admin\Controller\Component\ActionComponent $Admin
 */
class CronController extends \Admin\Controller\Controller
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
