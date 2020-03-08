<?php
use Cake\Cache\Cache;
use Cake\Log\Log;

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
