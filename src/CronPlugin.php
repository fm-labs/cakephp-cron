<?php

namespace Cron;


use Banana\Plugin\PluginInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;

class CronPlugin implements PluginInterface, EventListenerInterface
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
            'Backend.Menu.get' => ['callable' => 'getBackendMenu', 'priority' => 90 ]
        ];
    }

    public function getBackendMenu(Event $event)
    {
        $event->subject()->addItem([
            'title' => 'Cron',
            'url' => ['plugin' => 'Cron', 'controller' => 'Cron', 'action' => 'index'],
            'data-icon' => 'clock-o',
            'children' => [
            ],
        ]);
    }

    /**
     * @param array $config
     * @return void
     */
    public function __invoke(array $config = [])
    {
    }
}