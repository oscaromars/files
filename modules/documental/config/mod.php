<?php
return ['documental' => [
            'class' => 'app\modules\documental\Module',
            'db_documental' => [
                'class' => 'app\components\CConnection',
                'dsn' => 'mysql:host=localhost;dbname=db_documental',
                'username' => 'uteg',
                'password' => 'Utegadmin2016*',
                'charset' => 'utf8',
                'dbname' => 'db_documental',
                'dbserver' => 'localhost'
                ],
            ],
        ];