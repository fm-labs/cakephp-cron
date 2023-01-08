<?php

namespace Cron;

use Cake\Core\StaticConfigTrait;
use Cron\Cron\CronTaskRegistry;
use Cron\Cron\CronTaskResult;
use Cron\Cron\ICronTask;

class Cron
{
    use StaticConfigTrait;

    /**
     * @var bool Global cron execution flag
     */
    protected static $_enabled = true;

    /**
     * Cache Registry used for creating and using cron tasks.
     *
     * @var \Cron\Cron\CronTaskRegistry|null
     */
    protected static $_registry;

    /**
     * Returns the Cache Registry instance used for creating and using cache adapters.
     *
     * @return \Cron\Cron\CronTaskRegistry
     */
    public static function getRegistry(): CronTaskRegistry
    {
        if (static::$_registry === null) {
            static::$_registry = new CronTaskRegistry();
        }

        return static::$_registry;
    }

    /**
     * Sets the Cache Registry instance used for creating and using cache adapters.
     *
     * Also allows for injecting of a new registry instance.
     *
     * @param \Cron\Cron\CronTaskRegistry $registry Injectable registry object.
     * @return void
     */
    public static function setRegistry(CronTaskRegistry $registry): void
    {
        static::$_registry = $registry;
    }

    public static function run($config): ?CronTaskResult
    {
        $task = self::getTask($config);

        if ($task !== null) {
            $meta = [];
            try {
                $meta['time_start'] = time();
                $result = $task->execute();
                $meta['time_end'] = time();

                $result->setMetaData($meta);
                return $result;
            } catch (\Exception $ex) {
                return new CronTaskResult(false, $ex->getMessage());
            }
        }
        return null;
    }

    public static function getTask($config): ?ICronTask
    {
        if (!static::$_enabled) {
            return null;
        }

        $registry = static::getRegistry();

        if (isset($registry->{$config})) {
            return $registry->{$config};
        }

        static::_initTask($config);

        return $registry->{$config};
    }

    protected static function _initTask(string $name): void
    {
        $registry = static::getRegistry();

        if (empty(static::$_config[$name]['className'])) {
            throw new \InvalidArgumentException(
                sprintf('The "%s" cron configuration is invalid.', $name)
            );
        }

        /** @var array $config */
        $config = static::$_config[$name];

        try {
            $registry->load($name, $config);
        } catch (\RuntimeException $e) {
            // @TODO: Handle exception
            throw $e;
        }
    }
}