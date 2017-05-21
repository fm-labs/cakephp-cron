<?php
return ['Cron' => [

    'Tasks' => [
        'daily' => [
            'className' => 'Cron.Daily'
        ],
        'weekly' => [
            'className' => 'Cron.Weekly'
        ],
        'custom' => [
            'className' => 'MyPlugin.MyCron'
        ]
    ]

]];