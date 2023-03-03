<?php
declare(strict_types=1);

namespace Cron;

use Admin\Core\BaseAdminPlugin;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/**
 * Class Plugin
 *
 * @package Cron
 */
class CronAdmin extends BaseAdminPlugin implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->fallbacks(DashedRoute::class);
    }

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Admin.Menu.build.admin_primary' => ['callable' => 'buildAdminMenu' /*, 'priority' => 5*/ ],
            'Admin.Menu.build.admin_system' => ['callable' => 'buildAdminSystemMenu' /*, 'priority' => 5*/ ],
        ];
    }

    /**
     * @param \Cake\Event\Event $event Event
     * @param \Cupcake\Menu\MenuItemCollection $menu Menu
     * @return void
     */
    public function buildAdminMenu(Event $event, \Cupcake\Menu\MenuItemCollection $menu): void
    {
    }

    /**
     * @param \Cake\Event\Event $event
     * @param \Cupcake\Menu\MenuItemCollection $menu
     * @return void
     */
    public function buildAdminSystemMenu(Event $event, \Cupcake\Menu\MenuItemCollection $menu): void
    {
        $menu->addItem([
            'title' => __d('cron', 'Scheduled Tasks'),
            'url' => ['plugin' => 'Cron', 'controller' => 'CronManager', 'action' => 'index'],
            'data-icon' => 'clock-o',
        ]);
    }
}
