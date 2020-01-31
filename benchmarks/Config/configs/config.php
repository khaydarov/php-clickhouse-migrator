<?php

return [
    'default' => 'development',
    'paths' => [
        'migrations' => 'migrations'
    ],
    'environments' => [
        'development' => [
            'cluster' => 'production',
            'host' => 'host',
            'port' => 'port',
            'database' => 'database',
            'username' => 'username',
            'password' => 'password'
        ]
    ]
];