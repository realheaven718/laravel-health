<?php

return [

    /*
     * A result store is responsible for saving the results of the checks. The
     * `EloquentHealthResultStore` will save results in the database. You
     * can use multiple stores at the same time.
     */
    'result_stores' => [
        Spatie\Health\ResultStores\EloquentHealthResultStore::class,

        /*
        Spatie\Health\ResultStores\EloquentHealthResultStore\JsonFileHealthResultStore::class => [
            'disk' => 's3',
            'path' => 'health.json',
        ],

        */
    ],


    /*
     * The amount of days the `EloquentHealthResultStore` will keep history
     * before pruning items.
     */
    'keep_history_for_days' => 5,

    /*
         * You can get notified when specific events occur. Out of the box you can use 'mail' and 'slack'.
         * For Slack you need to install laravel/slack-notification-channel.
         *
         * You can also use your own notification classes, just make sure the class is named after one of
         * the `Spatie\Backup\Notifications\Notifications` classes.
         */
    'notifications' => [

        'notifications' => [
            Spatie\Health\Notifications\CheckFailedNotification::class => ['mail'],
        ],

        /*
         * Here you can specify the notifiable to which the notifications should be sent. The default
         * notifiable will use the variables specified in this config file.
         */
        'notifiable' => Spatie\Health\Notifications\Notifiable::class,

        /*
         * When checks start failing, you could potentially end up getting
         * a notification every minute.
         *
         * With this setting, notifications are throttled. By default, you'll
         * only get one notification per hour.
         */
        'throttle_notifications_for_minutes' => 60,

        'mail' => [
            'to' => 'your@example.com',

            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Example'),
            ],
        ],

        'slack' => [
            'webhook_url' => '',

            /*
             * If this is set to null the default channel of the webhook will be used.
             */
            'channel' => null,

            'username' => null,

            'icon' => null,
        ],
    ],
];
