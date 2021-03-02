<?php

Yii::setAlias('@views', dirname(__DIR__) . '/views');
Yii::setAlias('@assets', dirname(__DIR__) . '/assets');
Yii::setAlias('@widgets', dirname(__DIR__) . '/widgets');
Yii::setAlias('@themes', dirname(__DIR__) . '/themes');
Yii::setAlias('@modules', dirname(__DIR__) . '/modules');
Yii::setAlias('@web', dirname(__DIR__) . '/web');

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'baseUrl' => '/asgard',
            'rules' => [],
        ],
    ],
    'params' => $params,
        /*
          'controllerMap' => [
          'fixture' => [ // Fixture generation command line.
          'class' => 'yii\faker\FixtureController',
          ],
          ],
         */
];

/******************************************************************************/
// se agregan multiples base de datos
/******************************************************************************/
$dir_data = __DIR__ . '/../data/';
$listFiles = scandir($dir_data);
$urlDir = "";
foreach ($listFiles as $key) {
    if (preg_match("/\.php$/", strtolower(trim($key)))) {
        $arr_data = require($dir_data . $key);
        $arr_key = array_keys($arr_data);
        $item = str_replace(".php", "", strtolower(trim($key)));
        $config['components'][$item] = $arr_data;
    }
}
/******************************************************************************/
// se agregan listado de modulos y configuracion de urlmanager rules
/******************************************************************************/
$dir_mod = __DIR__ . '/../modules/';
$listDirs = scandir($dir_mod);
$arr_rules = $config['components']['urlManager']['rules'];
$arr_new_rules = array();
$arr_new_items = array();
foreach ($listDirs as $modulo) {// se obtiene los directorios dentro de modules
    if($modulo != "." && $modulo != ".."){
        $modFile = $dir_mod . $modulo . "/config/mod.php";
        $conFile = $dir_mod . $modulo . "/config/config.php";
        if(is_file($modFile)){
            $arr_mod = require($modFile);
            $config['modules'][$modulo] = $arr_mod[$modulo];
        }
        if(is_file($conFile)){
            $arr_conf = require($conFile);
            if(isset($arr_conf['components']['urlManager']['rules'])){
                $url_manager = $arr_conf['components']['urlManager']['rules'];
                $arr_new_rules = array_merge($arr_new_rules, $url_manager);
            }
            foreach($arr_conf['components'] as $keyC => $valueC){
                if ($keyC != "urlManager") {
                    $comp_manager[$keyC] = $arr_conf['components'][$keyC];
                    $arr_new_items = array_merge($arr_new_items, $comp_manager);
                }
            }
        }
    }
}
$config['components']['urlManager']['rules'] = array_merge($arr_rules, $arr_new_rules);
$arr_comp = $config['components'];
$config['components'] = array_merge($arr_comp, $arr_new_items);
/******************************************************************************/

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
