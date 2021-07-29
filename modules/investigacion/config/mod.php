<?php
return ['investigacion' => [
            'class' => 'app\modules\investigacion\Module',
            'db_investigacion' => [
                'class' => 'app\components\CConnection',
                'dsn' => 'mysql:host=localhost;dbname=db_investigacion',
                'username' => 'uteg',
                'password' => 'Utegadmin2016*',
                'charset' => 'utf8',
                'dbname' => 'db_investigacion',
                'dbserver' => 'localhost'
                ],
            ],
        ];