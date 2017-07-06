<?php

namespace Cron\Action;

use Backend\Action\BaseEntityAction;
use Cake\Controller\Controller;
use Cake\Event\Event;

class CronRunAction extends BaseEntityAction
{

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('Run cron task');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => null, 'target' => '_blank'];
    }

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        $entity = $this->entity();
        return $controller->redirect([
            'prefix' => false, 'plugin' => 'Cron', 'controller' => 'Cron', 'action' => $entity->id
        ]);
    }
}