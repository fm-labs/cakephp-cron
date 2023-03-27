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
                'Cron.notifyOnSuccess' => [
                    'group' => 'Cron',
                    'type' => 'boolean',
                    'default' => false,
                    'label' => __d('cron', 'Notify On Success'),
                    'help' => __d('cron', 'Send result notifications to admins on success'),
                ],
                'Cron.notifyOnError' => [
                    'group' => 'Cron',
                    'type' => 'boolean',
                    'default' => false,
                    'label' => __d('cron', 'Notify On Error'),
                    'help' => __d('cron', 'Send result notifications to admins on error'),
                ],
                'Cron.emailProfile' => [
                    'group' => 'Cron',
                    'type' => 'string',
                    'default' => 'admin',
                    'label' => __d('cron', 'Email profile'),
                    'help' => __d('cron', 'Name of the email profile to use, when sending result notifications'),
                ],
//                'Cron.WebRunner.enabled' => [
//                    'group' => 'Cron',
//                    'type' => 'string',
//                    'default' => 'admin',
//                    'label' => __d('cron', 'Web Runner Enabled'),
//                    'help' => __d('cron', 'If enabled, cron task can be triggered via URL (/cron)'),
//                ],
                'Cron.WebRunner.token' => [
                    'group' => 'Cron',
                    'type' => 'string',
                    'default' => '',
                    'label' => __d('cron', 'Web Runner Token'),
                    'help' => __d('cron', 'This token will be required in the web runner url (/cron/?token=TOKENHERE)'),
                ],
            ],
        ],
    ],
];
