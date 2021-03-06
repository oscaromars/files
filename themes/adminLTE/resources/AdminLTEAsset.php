<?php
/*
 * The PenBlu framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2015 by PenBlu Software (http://www.penblu.com)
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions 
 * are met:
 *
 *  - Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *  - Neither the name of PenBlu Software nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PenBlu is based on code by 
 * Yii Software LLC (http://www.yiisoft.com) Copyright © 2008
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * Authors:
 *
 * Eduardo Cueva <ecueva@penblu.com>
 * 
 * Update:
 * 
 * Diana Lopez <dlopez@uteg.edu.ec>
 * 
 */

namespace app\themes\adminLTE\resources;

use Yii;
use yii\web\AssetBundle;

/**
 * Configuration for `backend` client script files
 * @since 4.0
 */
class AdminLTEAsset extends AssetBundle
{
	public function init()
    {
		parent::init();
	}
	
    public $sourcePath = '@themes/adminLTE/assets';
    public $baseUrl = '@web';
    public $css = [
        'css/AdminLTE.min.css', 
        'css/ionicons.min.css', 
        'css/skins/_all-skins.min.css',
        'css/styleLTE.css',
        'plugins/iCheck/square/blue.css',
        'plugins/colorpicker/bootstrap-colorpicker.min.css',
        'plugins/datepicker/datepicker3.css',
        'plugins/timepicker/bootstrap-timepicker.min.css',
        ];
    public $js = [
        'js/app.min.js',  
        'plugins/fastclick/fastclick.min.js',
        'plugins/iCheck/icheck.min.js',
        'plugins/colorpicker/bootstrap-colorpicker.min.js',
        'plugins/browser-detect/browser-detect.js',
        'plugins/jsession-timeout/jSessionTimeOut.js',
        'plugins/date-format/date.format.js',
        'plugins/slimScroll/jquery.slimscroll.min.js',
        'plugins/datepicker/bootstrap-datepicker.js',
        'plugins/timepicker/bootstrap-timepicker.min.js',
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
