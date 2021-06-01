<?php
return ['gpr' => [
            'class' => 'app\modules\gpr\Module',
            'db_gpr' => [
                'class' => 'app\components\CConnection',
                'dsn' => 'mysql:host=localhost;dbname=db_gpr',
                'username' => 'uteg',
                'password' => 'Utegadmin2016*',
                'charset' => 'utf8',
                'dbname' => 'db_gpr',
                'dbserver' => 'localhost'
                ],
            ],
        ];