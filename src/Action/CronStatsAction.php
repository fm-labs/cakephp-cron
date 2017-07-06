<?php

namespace Cron\Action;

use Backend\Action\BaseEntityAction;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

class CronStatsAction extends BaseEntityAction
{

    /**
     * {@inheritDoc}
     */
    public function getLabel()
    {
        return __('View cron stats');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return ['data-icon' => null];
    }

    /**
     * {@inheritDoc}
     */
    public function _execute(Controller $controller)
    {
        $entity = $this->entity();
        $date = $controller->request->query('date');
        //@todo validate date input format
        $date = ($date) ?: date('Y-m-d');
        $filepath = TMP . 'cron' . DS . $entity->id . '_' . $date . '.csv';

        try {
            $tableAlias = 'CronStatus' . Inflector::camelize($entity->id);

            TableRegistry::config($tableAlias, [
                'className' => 'Cron.CronStats',
                'file' => $filepath,
                'columns' => ['timestamp', 'status', 'text'],
                'displayField' => 'task',
                'schema' => []
            ]);

            $CronStats = $controller->loadModel($tableAlias);
            $stats = $CronStats->find()->all();
            $controller->set(compact('stats'));

        } catch (\Exception $ex) {
            $controller->Flash->error($ex->getMessage());
        }

        $controller->viewBuilder()->plugin('Cron');
        $controller->viewBuilder()->templatePath('Admin/Cron');
        $controller->render('stats');
    }
}