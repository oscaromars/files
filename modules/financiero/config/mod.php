<?php
return ['financiero' => [
            'class' => 'app\modules\financiero\Module',
            'db_facturacion' => [
                'class' => 'app\components\CConnection',
                'dsn' => 'mysql:host=localhost;dbname=db_facturacion',
                'username' => 'uteg',
                'password' => 'Utegadmin2016*',
                'charset' => 'utf8',
                'dbname' => 'db_facturacion',
                'dbserver' => 'localhost'
                ],            
            ],
        ];
