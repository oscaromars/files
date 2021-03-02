<?php
/*
 * The Asgard framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2015 by Asgard Software 
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
 *  - Neither the name of Asgard Software nor the names of its
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
 * Asgard is based on code by
 * Yii Software LLC (http://www.yiisoft.com) Copyright Â© 2008
 *
 * Authors:
 * 
 * Kleber Loayza <kloayza@uteg.edu.ec>
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;

financiero::registerTranslations();
academico::registerTranslations();
?>

<form class="form-horizontal" enctype="multipart/form-data" >
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Request Data") ?></span></h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"><?= Yii::t("formulario", "Fields with * are required") ?> </p>        
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_empresa" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label keyupmce"><?= Yii::t("formulario", "Company") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?= Html::dropDownList("cmb_empresa", 1, $arr_empresa, ["class" => "form-control", "id" => "cmb_empresa",]) ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">                        
                <label for="txt_fecha_solicitud" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("formulario", "Fecha Solicitud") ?></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_solicitud',
                        'value' => '',
                        //'disabled' => $habilita,
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "txt_fecha_solicitud", "data-type" => "fecha_pro", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_unidad_solicitudw" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("formulario", "Academic unit") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">                    
                    <?= Html::dropDownList("cmb_unidad_solicitudw", 0, array_merge([Yii::t("formulario", "Select")], $arr_ninteres), ["class" => "form-control", "id" => "cmb_unidad_solicitudw"]) ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_modalidad_solicitudw" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("formulario", "Mode") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">                    
                    <?= Html::dropDownList("cmb_modalidad_solicitudw", 0, array_merge([Yii::t("formulario", "Select")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad_solicitudw"]) ?> 
                </div>
            </div>        
        </div>     
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_carrera_solicitudw" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Yii::t("academico", "Career") . ' /Programa' ?> <span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">                    
                    <?= Html::dropDownList("cmb_carrera_solicitudw", 0, array_merge([Yii::t("formulario", "Select")], $arr_carrerra1), ["class" => "form-control", "id" => "cmb_carrera_solicitudw"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" id="divMetodo" style="display: block">
            <div class="form-group">            
                <label for="cmb_metodo_solicitudw" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= Yii::t("formulario", "Income Method") ?><span class="text-danger">*</span></label>
                <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">                    
                    <?= Html::dropDownList("cmb_metodo_solicitudw", 0, array_merge([Yii::t("formulario", "Select")], $arr_metodos), ["class" => "form-control", "id" => "cmb_metodo_solicitudw"]) ?>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divItem" style="display: block">        
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_item_solicitudw" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= financiero::t("Pagos", "Item") ?></label>
                <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">                    
                    <?= Html::dropDownList("cmb_item_solicitudw", 0,  array_merge([Yii::t("formulario", "Select")], $arr_item), ["class" => "form-control", "id" => "cmb_item_solicitudw"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_precio_itemw" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_nombre1"><?= financiero::t("Pagos", "Price") ?></label>
                <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
                    <input type="text" class="form-control keyupmce" value="0" id="txt_precio_itemw" disabled data-type="alfa" align="rigth" placeholder="<?= financiero::t("Pagos", "Price") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divBeca" style="display: none">        
        <div class="form-group">
            <label for="txt_declararbecaw" class="col-sm-5 control-label"><?= admision::t("Solicitudes", "Apply Cala Foundation scholarship") ?></label>
            <div class="col-sm-7">  
                <label><input type="radio" name="opt_declara_si"  id="opt_declara_si" value="1"><b>Si</b></label>
                <label><input type="radio" name="opt_declara_no"  id="opt_declara_no" value="2" checked><b>No</b></label>                                              
            </div>            
        </div>        
    </div>
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divAplicaDescuento" style="display: block">               
        <div class="form-group">
            <label for="txt_declararDescuentow" class="col-sm-3 control-label"><?= financiero::t("Pagos", "Apply Discount") ?></label>
            <div class="col-sm-9">  
                <label><input type="radio" name="opt_declara_Dctosi"  id="opt_declara_Dctosi" value="1"><b>Si</b></label>
                <label><input type="radio" name="opt_declara_Dctono"  id="opt_declara_Dctono" value="2" checked><b>No</b></label>                                              
            </div>            
        </div>               
    </div> 
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12" id="divDescuento" style="display: none">    
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_descuento_solicitudw" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label keyupmce"><?= financiero::t("Pagos", "Discount") ?></label>
                <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">                    
                    <?= Html::dropDownList("cmb_descuento_solicitudw", 0, array_merge([Yii::t("formulario", "Select")], $arr_descuento), ["class" => "form-control", "id" => "cmb_descuento_solicitudw"]) ?>
                </div>
            </div>    
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_precio_item2w" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_nombre1"><?= financiero::t("Pagos", "Price with discount") ?></label>
                <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
                    <input type="text" class="form-control keyupmce" value="0" id="txt_precio_item2w" disabled data-type="alfa" align="rigth" placeholder="<?= financiero::t("Pagos", "Price") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_observacionw" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_observacion"><?= Yii::t("formulario", "Observations") ?></label>
                <div class="col-sm-9 col-md-9 col-xs-9 col-lg-9">
                    <!--<input type="text" class="form-control keyupmce" id="txt_observacion" data-type="alfa" placeholder="<? Yii::t("formulario", "Observations") ?>">-->
                    <textarea  class="form-control keyupmce" id="txt_observacionw" rows="3"></textarea>
                </div>
            </div>
        </div> 
    </div>        
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">         
    </div>
    <div class="row"> 
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"></div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <a id="sendInformacionAdmitidoFinal" href="javascript:" class="btn btn-primary btn-block"> <?php echo "Registrar"; ?> </a>
        </div>
    </div>
</form>