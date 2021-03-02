<?php
return ['fe_edoc' => [
            'class' => 'app\modules\fe_edoc\Module',
            'db_edoc' => [
                'class' => 'app\components\CConnection',
                'dsn' => 'mysql:host=localhost;dbname=db_edoc',
                //'username' => 'root',
                'username' => 'uteg',
                'password' => 'Utegadmin2016*',
                //'password' => 'root00',
                'charset' => 'utf8',
                'dbname' => 'db_edoc',
                'dbserver' => 'localhost'
                ],
            ],
        ];
