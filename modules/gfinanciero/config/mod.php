<?php
return ['gfinanciero' => [
            'class' => 'app\modules\gfinanciero\Module',
            'db_gfinanciero' => [
                'class' => 'app\components\CConnection',
                'dsn' => 'mysql:host=localhost;dbname=db_gfinanciero',
                'username' => 'uteg',
                'password' => 'Utegadmin2016*',
                'charset' => 'utf8',
                'dbname' => 'db_gfinanciero',
                'dbserver' => 'localhost'
                ],
            ],
        ];
