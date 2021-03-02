<?php
return ['marcacionhistorico' => [
            'class' => 'app\modules\marcacionhistorico\Module',                        
            'db_marcacion_historico' => [
                'class' => 'app\components\CConnection',
                'dsn' => 'mysql:host=localhost;dbname=db_marcacion_historico',
                'username' => 'uteg',
                'password' => 'Utegadmin2016*',
                'charset' => 'utf8',
                'dbname' => 'db_marcacion_historico',
                'dbserver' => 'localhost'
                ],
            ],
        ];