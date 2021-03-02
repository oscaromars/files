<?php

namespace app\widgets\PbVPOS\assets;

use yii\web\AssetBundle;

class VPOSAsset extends AssetBundle
{
    public $sourcePath = '@widgets/PbVPOS';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => \yii\web\View::POS_END];
    public $css = [
        'css/style.css',
    ];
    public $js = [
        'js/vpos-lightbox.min.js',
        'js/vpos.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}