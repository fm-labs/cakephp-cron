<?php
return [
    'Settings' => [
        'Cron' => [
            'groups' => [
                'Cron' => [
                    'label' => __d('cron', 'Cron Settings'),
                ],
            ],

            'schema' => [
                'Cron.enabled' => [
                    'group' => 'Cron',
                    'type' => 'boolean',
                    'label' => __d('cron', 'Enable scheduled task execution'),
                    'help' => __d('cron', 'Run scheduled tasks in background at configured intervals'),
                ],
                'Cron.notify_on_error' => [
                    'group' => 'Cron',
                    'type' => 'boolean',
                    'label' => __d('cron', 'Notify On Error'),
                    'help' => __d('cron', 'Send result notifications to admins on error'),
                ],
                'Cron.notify_email' => [
                    'group' => 'Cron',
                    'type' => 'string',
                    'label' => __d('cron', 'Notify Email Address'),
                    'help' => __d('cron', 'Recipient email address of result notifications'),
                ],
            ],
        ],
    ],
];
