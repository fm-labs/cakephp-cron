<?php
use Cake\Cache\Cache;
use Cake\Log\Log;

// setup cron cache
if (!Cache::config('cron')) {
    Cache::config('cron', [
        'className' => 'File',
        'duration' => '+1 years',
        'path' => CACHE,
        'prefix' => 'cron_'
    ]);
}

// setup cron log
if (!Log::config('cron')) {
    Log::config('cron', [
        'className' => 'Cake\Log\Engine\FileLog',
        'path' => LOGS,
        'file' => 'cron',
        //'levels' => ['notice', 'info', 'debug'],
        'scopes' => ['cron']
    ]);
}