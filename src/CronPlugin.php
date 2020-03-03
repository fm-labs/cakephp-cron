<?php

namespace Cron;

use Backend\Backend;
use Backend\BackendPluginInterface;
use Backend\Event\RouteBuilderEvent;
use Banana\Application;
use Banana\Plugin\PluginInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

/**
 * Class CronPlugin
 *
 * @package Cron
 */
class CronPlugin implements PluginInterface, BackendPluginInterface, EventListenerInterface
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
            'Backend.Menu.build.admin_primary' => ['callable' => 'buildBackendSidebarMenu', 'priority' => 90 ],
            //'Backend.Menu.build.admin_system' => ['callable' => 'buildBackendSystemMenu', 'priority' => 90 ],
            //'Backend.Routes.build' => 'buildBackendRoutes'
        ];
    }

    /*
    public function buildBackendRoutes(RouteBuilderEvent $event)
    {
        $event->subject()->scope('/cron', ['plugin' => 'Cron', '_namePrefix' => 'cron:admin:', 'prefix' => 'admin'], function($routes) {
            //$routes->connect('/:controller');
            $routes->fallbacks('DashedRoute');
        });
    }
    */

    /**
     * @param Event $event
     */
    public function buildBackendSidebarMenu(Event $event)
    {
        if ($event->subject() instanceof \Banana\Menu\Menu) {
            $event->subject()->addItem([
                'title' => 'Cron Jobs',
                'url' => ['plugin' => 'Cron', 'controller' => 'CronJobs', 'action' => 'index'],
                'data-icon' => 'clock-o',
            ]);
        }
    }

    public function buildBackendSystemMenu(Event $event)
    {
    }

    public function bootstrap(Application $app)
    {
        EventManager::instance()->on($this);
    }

    public function routes(RouteBuilder $routes)
    {
    }

    public function middleware(MiddlewareQueue $middleware)
    {
    }

    public function backendBootstrap(Backend $backend)
    {
    }

    public function backendRoutes(RouteBuilder $routes)
    {
        $routes->fallbacks('DashedRoute');
    }
}
