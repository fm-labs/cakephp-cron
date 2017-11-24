<?php

namespace Cron\Cron;


use Cake\Core\App;
use Cake\Core\ObjectRegistry;
use RuntimeException;

/**
 * Class CronTaskRegistry
 *
 * @package Cron\Cron
 */
class CronTaskRegistry extends ObjectRegistry
{

    /**
     * Resolve a cron task classname.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class Partial classname to resolve.
     * @return string|false Either the correct classname or false.
     */
    protected function _resolveClassName($class)
    {
        if (is_object($class)) {
            return $class;
        }
        return App::className($class, 'Cron/Task', 'CronTask');
    }

    /**
     * Throws an exception when a cron task is missing.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class The classname that is missing.
     * @param string $plugin The plugin the cron task is missing in.
     * @return void
     * @throws \RuntimeException
     */
    protected function _throwMissingClassError($class, $plugin)
    {
        throw new RuntimeException(sprintf('Could not load class %s', $class));
    }

    /**
     * Create the cron task instance.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string|\Psr\Log\LoggerInterface $class The classname or object to make.
     * @param string $alias The alias of the object.
     * @param array $settings An array of settings to use for the cron task.
     * @return \Psr\Log\LoggerInterface The constructed cron task class.
     * @throws \RuntimeException when an object doesn't implement the correct interface.
     */
    protected function _create($class, $alias, $settings)
    {
        if (is_callable($class)) {
            $class = $class($alias);
        }

        if (is_object($class)) {
            $instance = $class;
        }

        if (!isset($instance)) {
            $instance = new $class($settings);
        }

        if ($instance instanceof CronTask) {
            return $instance;
        }

        throw new RuntimeException(
            'Object must extend CronTask class.'
        );
    }

    /**
     * Get loaded cron task instance
     *
     * @param string $name
     * @return null|CronTask
     */
    public function get($name)
    {
        return parent::get($name);
    }
}