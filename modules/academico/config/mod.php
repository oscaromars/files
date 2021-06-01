<?php
return ['academico' => [
            'class' => 'app\modules\academico\Module',
            'db_academico' => [
                'class' => 'app\components\CConnection',
                'dsn' => 'mysql:host=localhost;dbname=db_academico',
                'username' => 'uteg',
                'password' => 'Utegadmin2016*',
                'charset' => 'utf8',
                'dbname' => 'db_academico',
                'dbserver' => 'localhost'
                ],
            ],
        ];