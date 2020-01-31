<?php

return [
    'default' => 'development',
    'paths' => [
        'migrations' => 'migrations'
    ],
    'environments' => [
        'development' => [
            'cluster' => 'development',
            'host' => 'host',
            'port' => 2211,
            'database' => 'database',
            'username' => 'username',
            'password' => 'password'
        ],
        'production' => [
            'cluster' => 'production',
            'host' => 'production.host',
            'port' => 1122,
            'database' => 'database',
            'username' => 'username',
            'password' => 'password'
        ]
    ]
];