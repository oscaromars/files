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
class StripeAsset extends AssetBundle {

    public $sourcePath = '@assets/assets';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'https://js.stripe.com/v3/',
        'https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch',
        'js/stripe.js',
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
