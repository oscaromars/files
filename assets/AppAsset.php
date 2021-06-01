<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle {

    public $sourcePath = '@assets/assets';
    public $baseUrl = '@web';
    public $css = [
        'css/PBstyles.css',
        'css/PBvalida.css',
        'plugins/select2/css/select2.min.css',
        'plugins/bootstrap-toggle/css/bootstrap-toggle.min.css',
    ];
    public $js = [
        'js/PBscripts.js',
        'js/PBvalidation.js',
        'js/cedulaRucPass.js',
        'js/base64.js',
        'plugins/select2/js/select2.full.min.js',
        'plugins/bootstrap-toggle/js/bootstrap-toggle.min.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_END];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}
