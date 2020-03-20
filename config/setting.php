<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 默认设置存储
    |--------------------------------------------------------------------------
    */
    'default' => env('SETTING_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | 设置存储
    |--------------------------------------------------------------------------
    */
    'stores' => [
        'database' => [
            'driver' => 'database',
            'table' => env('SETTING_DATABASE_TABLE', 'settings'),
            'connection' => env('DB_CONNECTION', 'mysql'),
        ],
    ],
];