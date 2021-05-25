<?php
return ['marketing' => [
            'class' => 'app\modules\marketing\Module',
            'db_mailing' => [
                'class' => 'app\components\CConnection',
                'dsn' => 'mysql:host=localhost;dbname=db_mailing',
                'username' => 'uteg',
                'password' => 'Utegadmin2016*',
                'charset' => 'utf8',
                'dbname' => 'db_mailing',
                'dbserver' => 'localhost'
                ],
            ],
        ];
