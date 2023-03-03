<?php
declare(strict_types=1);

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
    protected function _resolveClassName(string $class): ?string
    {
        return App::className($class, 'Cron', 'CronTask');
    }

    /**
     * Throws an exception when a cron task is missing.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class The classname that is missing.
     * @param string|null $plugin The plugin the cron task is missing in.
     * @return void
     */
    protected function _throwMissingClassError(string $class, ?string $plugin): void
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
     * @param array $config An array of config to use for the cron task.
     * @return \Psr\Log\LoggerInterface The constructed cron task class.
     * @throws \RuntimeException when an object doesn't implement the correct interface.
     */
    protected function _create($class, string $alias, array $config)
    {
        if (is_callable($class)) {
            $class = $class($alias);
        }

        if (is_object($class)) {
            $instance = $class;
        }

        if (!isset($instance)) {
            $instance = new $class($config);
        }

        if ($instance instanceof ICronTask) {
            return $instance;
        }

        throw new RuntimeException(
            __d('cron','Object must be an instance of ICronTask class.')
        );
    }

    /**
     * Get loaded cron task instance
     *
     * @param string $name
     * @return \Cron\Cron\ICronTask
     */
    public function get(string $name): ICronTask
    {
        return parent::get($name);
    }
}
