<?php

return [
    1 => [ // Empresa con ID 1
        'class' => 'app\components\CConnection',
        'dsn' => 'mysql:host=localhost;dbname=db_gfinanciero',
        'username' => 'uteg',
        'password' => 'Utegadmin2016*',
        'charset' => 'utf8',
        'dbname' => 'db_gfinanciero',
        'dbserver' => 'localhost'
    ],
    2 => [ // Empresa con ID 2
        'class' => 'app\components\CConnection',
        'dsn' => 'mysql:host=localhost:8083;dbname=db_gfinanciero',
        'username' => 'uteg',
        'password' => 'Utegadmin2016*',
        'charset' => 'utf8',
        'dbname' => 'db_gfinanciero',
        'dbserver' => 'localhost:8083'
    ],
];