<?php

namespace Cron;

use Banana\Plugin\PluginInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Routing\Router;

/**
 * Class CronPlugin
 *
 * @package Cron
 */
class CronPlugin implements EventListenerInterface
{

    /**
     * @param EventManager $eventManager
     * @return $this
     */
    public function registerEvents(EventManager $eventManager)
    {
    }

    /**
     * Returns a list of events this object is implementing. When the class is registered
     * in an event manager, each individual method will be associated with the respective event.
     *
     * @see EventListenerInterface::implementedEvents()
     * @return array associative array or event key names pointing to the function
     * that should be called in the object when the respective event is fired
     */
    public function implementedEvents()
    {
        return [
            'Backend.Menu.get' => ['callable' => 'getBackendMenu', 'priority' => 90 ],
            'Backend.Routes.build' => 'buildBackendRoutes'
        ];
    }

    public function buildBackendRoutes()
    {
        Router::scope('/cron/admin', ['plugin' => 'Cron', '_namePrefix' => 'cron:admin:', 'prefix' => 'admin'], function($routes) {
            $routes->connect('/:controller');
            $routes->fallbacks('DashedRoute');
        });
    }

    /**
     * @param Event $event
     */
    public function getBackendMenu(Event $event)
    {
        $event->subject()->addItem([
            'title' => 'Cron',
            'url' => ['plugin' => 'Cron', 'controller' => 'Cron', 'action' => 'index'],
            'data-icon' => 'clock-o',
            'children' => [
                'cron_tasks' => [
                    'title' => 'Cron Tasks',
                    'url' => ['plugin' => 'Cron', 'controller' => 'Cron', 'action' => 'index'],
                    'data-icon' => 'clock-o',
                ],
                'cron_stats' => [
                    'title' => 'Cron Stats',
                    'url' => ['plugin' => 'Cron', 'controller' => 'CronStats', 'action' => 'index'],
                    'data-icon' => 'clock-o',
                ]
            ],
        ]);
    }
}