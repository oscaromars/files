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
class DatatableAsset extends AssetBundle {

    public $sourcePath = '@assets/assets';
    public $baseUrl = '@web';

    public $css = [
        'DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css',
        'DataTables/Buttons-1.5.4/css/buttons.dataTables.min.css',
        'DataTables/Select-1.2.6/css/select.dataTables.min.css',
        'DataTables/Editor-1.9.4/css/editor.dataTables.min.css',
        'DataTables/Responsive-2.2.2/css/responsive.dataTables.css',
    ];

    public $js = [
        //'DataTables/moment-with-locales.js',
        'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js',
        'DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js',
        'DataTables/Buttons-1.5.4/js/dataTables.buttons.min.js',
        'DataTables/Select-1.2.6/js/dataTables.select.min.js',
        'DataTables/Editor-1.9.4/js/dataTables.editor.min.js',
        'DataTables/Responsive-2.2.2/js/dataTables.responsive.min.js',
    ];
    
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}
