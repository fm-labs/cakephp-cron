<?php

namespace Cron;

use Backend\Event\RouteBuilderEvent;
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
            'Backend.Sidebar.get' => ['callable' => 'getBackendSidebarMenu', 'priority' => 90 ],
            'Backend.Routes.build' => 'buildBackendRoutes'
        ];
    }

    public function buildBackendRoutes(RouteBuilderEvent $event)
    {
        $event->subject()->scope('/cron', ['plugin' => 'Cron', '_namePrefix' => 'cron:admin:', 'prefix' => 'admin'], function($routes) {
            //$routes->connect('/:controller');
            $routes->fallbacks('DashedRoute');
        });
    }

    /**
     * @param Event $event
     */
    public function getBackendSidebarMenu(Event $event)
    {
        $event->subject()->addItem([
            'title' => 'Cron Jobs',
            'url' => ['plugin' => 'Cron', 'controller' => 'CronJobs', 'action' => 'index'],
            'data-icon' => 'clock-o',
        ]);
    }
}