<?php
return ['formulario' => [
            'class' => 'app\modules\formulario\Module',
            'db_externo' => [
                'class' => 'app\components\CConnection',
                'dsn' => 'mysql:host=localhost;dbname=db_externo',
                'username' => 'uteg',
                'password' => 'Utegadmin2016*',
                'charset' => 'utf8',
                'dbname' => 'db_externo',
                'dbserver' => 'localhost'
                ],
            ],
        ];
