<?php
declare(strict_types=1);

namespace Cron;

use Cake\Cache\Cache;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Routing\RouteBuilder;

/**
 * Class CronPlugin
 *
 * @package Cron
 */
class Plugin extends BasePlugin
{
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        // setup cron cache
        if (!Cache::getConfig('cron')) {
            Cache::setConfig('cron', [
                'className' => 'File',
                'duration' => '+1 years',
                'path' => CACHE,
                'prefix' => 'cron_',
            ]);
        }

        // setup cron log
        if (!Log::getConfig('cron')) {
            Log::setConfig('cron', [
                'className' => 'Cake\Log\Engine\FileLog',
                'path' => LOGS,
                'file' => 'cron',
                //'levels' => ['notice', 'info', 'debug'],
                'scopes' => ['cron'],
            ]);
        }

        // register cron tasks for the cron plugin :)
        if (!Cron::getConfig('cron_debug')) {
            Cron::setConfig('cron_debug', [
                'className' => "\\Cron\\Cron\\Task\\DebugCronTask",
                'interval' => 'hourly' // month|week|day|hour|minute
            ]);
        }
        if (!Cron::getConfig('cron_debug2')) {
            Cron::setConfig('cron_debug2', [
                'className' => "\\Cron\\Cron\\Task\\DebugCronTask",
                'interval' => '*/5 * * * *' // Every 5 minutes in cron-tab notation
            ]);
        }
        if (!Cron::getConfig('cron_cleanup_cronjob_results')) {
            Cron::setConfig('cron_cleanup_cronjob_results', [
                'className' => "\\Cron\\Cron\\Task\\CleanupResultsCronTask",
                'interval' => 3600 // in seconds
            ]);
        }

        /**
         * Admin plugin
         */
        if (\Cake\Core\Plugin::isLoaded('Admin')) {
            \Admin\Admin::addPlugin(new \Cron\Admin());
        }

        //$eventManager = EventManager::instance();
        //$eventManager->on(new \Cron\Service\CronLoggingService());
        //$eventManager->on(new \Cron\Service\CronStatsService());
    }

    public function routes(RouteBuilder $routes): void
    {
        $routes->connect('/cron', ['controller' => 'Cron']);
        $routes->connect('/cron/:action', ['controller' => 'Cron']);
    }
}
