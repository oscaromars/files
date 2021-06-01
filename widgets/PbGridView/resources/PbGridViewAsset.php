<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\widgets\PbGridView\resources;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class PbGridViewAsset extends AssetBundle
{
    public $sourcePath = '@widgets/PbGridView/assets';
    public $baseUrl = '@web';
    public $css = ['css/PbGridView.css',];
    public $js = [
        'js/PbGridView.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
