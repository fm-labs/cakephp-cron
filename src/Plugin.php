<?php
declare(strict_types=1);

namespace Cron;

use Cupcake\Plugin\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Routing\Route\DashedRoute;

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
        return [
            'Admin.Menu.build.admin_primary' => ['callable' => 'buildAdminMenu', 'priority' => 90 ],
        ];
    }

    /**
     * @param \Cake\Event\Event $event
     */
    public function buildAdminMenu(Event $event, \Cupcake\Menu\MenuItemCollection $menu)
    {
        $menu->addItem([
            'title' => 'Cron Jobs',
            'url' => ['plugin' => 'Cron', 'controller' => 'CronJobs', 'action' => 'index'],
            'data-icon' => 'clock-o',
        ]);
    }

    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        EventManager::instance()->on($this);
    }

    public function adminRoutes($routes)
    {
        $routes->fallbacks(DashedRoute::class);
    }
}
