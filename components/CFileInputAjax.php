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
 *
 * Authors:
 *
 * Eduardo Cueva <ecueva@penblu.com>
 */

namespace app\components;

use Yii;
use kartik\file\FileInput;
use yii\helpers\Json;
use yii\web\View;

/**
 * Description of CFileInputAjax
 *
 * @author eduardocueva
 */
class CFileInputAjax extends FileInput{
    
    public $namevarjs = ""; // set name var javascript with parameter options
    
    public function init() {
        return parent::init();
    }
    
    protected function initInputWidget(){
        //$this->pluginOptions['uploadExtraData'] = "";
        return parent::initInputWidget();
    }
    
    /**
     * Generates a hashed variable to store the pluginOptions. The following special data attributes will also be setup
     * for the input widget, that can be accessed through javascript :
     *
     * - 'data-krajee-{name}' will store the hashed variable storing the plugin options. The `{name}` token will be
     *   replaced with the plugin name (e.g. `select2`, ``typeahead etc.). This fixes
     *   [issue #6](https://github.com/kartik-v/yii2-krajee-base/issues/6).
     *
     * @param string $name the name of the plugin
     */
    protected function hashPluginOptions($name)
    {
        //parent::hashPluginOptions($name);
        $uploadPostData = $this->pluginOptions["uploadExtraData"];
        if(preg_match('/^javascript:/', $uploadPostData)){
            unset($this->pluginOptions["uploadExtraData"]);
            $this->_encOptions = empty($this->pluginOptions) ? '' : Json::htmlEncode($this->pluginOptions);
            $this->_encOptions = substr_replace($this->_encOptions, "", -1) . ",'uploadExtraData': " .str_replace('javascript:', "", $uploadPostData) ."}";
        }else{
            $this->_encOptions = empty($this->pluginOptions) ? '' : Json::htmlEncode($this->pluginOptions);
        }
        $this->_hashVar = $name . '_' . (($this->namevarjs != "")?($this->namevarjs):(hash('crc32', $this->_encOptions)));
        $this->options['data-krajee-' . $name] = $this->_hashVar;
    }
    
    /**
     * Registers plugin options by storing within a uniquely generated javascript variable.
     *
     * @param string $name the plugin name
     */
    protected function registerPluginOptions($name)
    {
        //parent::registerPluginOptions($name);
        $view = $this->getView();
        $this->hashPluginOptions($name);
        $encOptions = empty($this->_encOptions) ? '{}' : $this->_encOptions;
        $this->registerWidgetJs('var '.$this->_hashVar.' = '.$encOptions.';', View::POS_HEAD);
        $view->registerCss("div.kv-upload-progress > div.progress{ margin-bottom: 5px; }");
    }
    
}
