<?php

namespace Cron\Controller\Admin;

use Backend\Controller\BackendActionsTrait;
use Backend\Controller\Component\ActionComponent;
use Backend\Controller\Component\BackendComponent;
use Cake\Controller\Controller;
use Cake\Core\Configure;

/**
 * Class AdminController
 *
 * @package Cron\Controller
 * @property ActionComponent $Action
 * @property BackendComponent $Backend
 */
class AdminController extends Controller
{
    use BackendActionsTrait;

    public $actions = [];

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
        $this->loadComponent('Backend.Action');
    }
}