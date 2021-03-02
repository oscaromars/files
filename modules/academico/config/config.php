<?php
$arr_conf = [
//return  [
    'components' => [
        // list of component configurations
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'rules' => [
                '<module:academico>/<controller:\w+>/<action:\w+>/<id:\w+>' => '<module>/<controller>/<action>',
            ]
        ]
    ],
    'params' => [
        // list of parameters
        'documentFolder' => '/uploads/',
    ],
];
$dir_data = __DIR__ . '/../data/';
$listFiles = scandir($dir_data);
foreach ($listFiles as $key) {
    if (preg_match("/\.php$/", strtolower(trim($key)))) {
        $arr_data = require($dir_data . $key);
        $arr_key = array_keys($arr_data);
        $item = str_replace(".php", "", strtolower(trim($key)));
        $arr_conf['components'][$item] = $arr_data;
    }
}
return $arr_conf;