<?php
return ['repositorio' => [
            'class' => 'app\modules\repositorio\Module',
            'db_repositorio' => [
                'class' => 'app\components\CConnection',
                'dsn' => 'mysql:host=localhost;dbname=db_repositorio',
                'username' => 'uteg',
                'password' => 'Utegadmin2016*',
                'charset' => 'utf8',
                'dbname' => 'db_repositorio',
                'dbserver' => 'localhost'
                ],
            ],
        ];
