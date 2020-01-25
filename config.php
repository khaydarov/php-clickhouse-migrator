<?php

return [
    'default_environment' => 'development',
    'paths' => [
        'migrations' => 'migrations'
    ],
    'environments' => [
        'development' => [
            'cluster' => 'local',
            'host' => 'localhost',
            'port' => '8123',
            'database' => 'golang_test',
            'username' => 'default',
            'password' => ''
        ]
    ]
];