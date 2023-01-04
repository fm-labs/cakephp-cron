<?php
declare(strict_types=1);

namespace Cron;

use Cake\Routing\RouteBuilder;
use Cupcake\Plugin\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;

/**
 * Class CronPlugin
 *
 * @package Cron
 */
class Plugin extends BasePlugin implements EventListenerInterface
{
    /**
     * Returns a list of events this object is implementing. When the class is registered
     * in an event manager, each individual method will be associated with the respective event.
     *
     * @see EventListenerInterface::implementedEvents()
     * @return array associative array or event key names pointing to the function
     * that should be called in the object when the respective event is fired
     */
    public function implementedEvents(): array
    {
        return [];
    }

    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);


        /**
         * Admin plugin
         */
        if (\Cake\Core\Plugin::isLoaded('Admin')) {
            \Admin\Admin::addPlugin(new \Cron\Admin());
        }

        $eventManager = EventManager::instance();
        $eventManager->on($this);

        EventManager::instance()->on($this);
    }

    public function routes(RouteBuilder $routes): void
    {
        $routes->connect('/', ['controller' => 'CronJobs']);
        $routes->connect('/:action', ['controller' => 'CronJobs']);
    }
}
