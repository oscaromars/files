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
 * Diana Lopez <dlopez@uteg.edu.ec>
 * Grace Viteri <analistadesarrollo01@uteg.edu.ec>
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\helpers\Url;
use app\components\CFileInputAjax;
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Additional") ?></span></h3>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <label for="txt_discapacidad" class="col-sm-5 control-label"><?= Yii::t("bienestar", "¿Do you have any type of disability?") ?></label>
        <div class="col-sm-7">           
            <label>
                <input type="radio" name="signup-dis" id="signup-dis" value="1"  <?php
                if ($discapacidadinte == 1) {
                    echo 'checked';
                }
                ?>> Si<br>
            </label>
            <label>
                <input type="radio" name="signup-dis_no" id="signup-dis_no" value="2"  <?php
                if ($discapacidadinte == "") {
                    echo 'checked';
                }
                ?>> No<br>
            </label>         
        </div> 
    </div>
</div>  

<?php if ($discapacidadinte ==1) { ?>
  <div id="discapacidad" style="display: block;" >
<?php } else { ?>
  <div id="discapacidad" style="display: none;" >
<?php } ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_tip_discap" class="col-sm-5 control-label keyupmce"><?= Yii::t("bienestar", "Type Disability") ?></label>
            <div class="col-sm-7">
                <?= Html::dropDownList("cmb_tip_discap", $tipo_discapacidad, $tipo_discap, ["class" => "form-control", "id" => "cmb_tip_discap"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_por_discapacidad" class="col-sm-5 control-label keyupmce"><?= Yii::t("bienestar", "Percentage Disability") ?></label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_por_discapacidad" value="<?= $porcentajeint ?>"  data-type="number" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Percentage Disability") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txth_doc_adj_disi" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
            <div class="col-sm-7">
                <?= Html::hiddenInput('txth_doc_adj_disi', $img_discapacidad, ['id' => 'txth_doc_adj_disi']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_adj_disi',
                    'name' => 'txt_doc_adj_disi',                
                    'pluginLoading' => false,
                    'showMessage' => false,
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showCancel' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                        'browseLabel' => "Subir Archivo",
                        'uploadUrl' => Url::to(['/ficha/guardarficha']),                   
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                return {"upload_file": true, "name_file": "enf_discapacidad"};
            }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
            $('#txth_doc_adj_disi').val($('#txt_doc_adj_disi').val());
            $('#txt_doc_adj_disi').fileinput('upload');
        }",
                        "fileuploaderror" => "function (event, data, msg) {
            $(this).parent().parent().children().first().addClass('hide');
            $('#txth_doc_adj_disi').val('');
            //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
        }",
                        "filebatchuploadcomplete" => "function (event, files, extra) { 
            $(this).parent().parent().children().first().addClass('hide');
        }",
                        "filebatchuploadsuccess" => "function (event, data, previewId, index) {
            var form = data.form, files = data.files, extra = data.extra,
            response = data.response, reader = data.reader;
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
        }",
                        "fileuploaded" => "function (event, data, previewId, index) {
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
        }",
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <hr>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <label for="txt_enfermedad" class="col-sm-5 control-label"><?= Yii::t("bienestar", "¿Do you have any catastrophic illness?") ?></label>
        <div class="col-sm-7">
            <label>
                <input type="radio" name="signup-enf" id="signup-enf" value="1"  <?php
                if ($enfermedadint == 1) {
                    echo 'checked';
                }
                ?>> Si<br>
            </label>
            <label>
                <input type="radio" name="signup-enf_no" id="signup-enf_no" value="2"  <?php
                if ($enfermedadint == "") {
                    echo 'checked';
                }
                ?>> No<br>
            </label>
        </div> 
    </div>
</div>
      
<?php if ($enfermedadint ==1) { ?>
  <div id="enfermedad" style="display: block;" >
<?php } else { ?>
  <div id="enfermedad" style="display: none;" >
<?php } ?>      
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txth_doc_adj_enfc" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
            <div class="col-sm-7">
                <?= Html::hiddenInput('txth_doc_adj_enfc', $img_enfermedad, ['id' => 'txth_doc_adj_enfc']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_adj_enfc',
                    'name' => 'txt_doc_adj_enfc',                
                    'pluginLoading' => false,
                    'showMessage' => false,
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showCancel' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                        'browseLabel' => "Subir Archivo",
                        'uploadUrl' => Url::to(['/ficha/guardarficha']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                return {"upload_file": true, "name_file": "enf_catastrofica"};
            }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
            $('#txth_doc_adj_enfc').val($('#txt_doc_adj_enfc').val());
            $('#txt_doc_adj_enfc').fileinput('upload');
        }",
                        "fileuploaderror" => "function (event, data, msg) {
            $('#txth_doc_adj_enfc').val('');
            $(this).parent().parent().children().first().addClass('hide');
            //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
        }",
                        "filebatchuploadcomplete" => "function (event, files, extra) { 
            $(this).parent().parent().children().first().addClass('hide');
        }",
                        "filebatchuploadsuccess" => "function (event, data, previewId, index) {
            var form = data.form, files = data.files, extra = data.extra,
            response = data.response, reader = data.reader;
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
        }",
                        "fileuploaded" => "function (event, data, previewId, index) {
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
        }",
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <hr>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <label for="txt_discapacidad_fam" class="col-sm-5 control-label"><?= Yii::t("bienestar", "¿Do you have a family member with Severe Disability?") ?></label>
        <div class="col-sm-7">
            <label>
                <input type="radio" name="signup-discf" id="signup-discf" value="1"  <?php
                if ($discapacidadfa == 1) {
                    echo 'checked';
                }
                ?>> Si<br>
            </label>
            <label>
                <input type="radio" name="signup-discf_no" id="signup-discf_no" value="2"  <?php
                if ($discapacidadfa == "") {
                    echo 'checked';
                }
                ?>> No<br>
            </label>
        </div> 
    </div>
</div>
<?php if ($discapacidadfa ==1) { ?>
  <div id="discapacidad_fam" style="display: block;" >
<?php } else { ?>
  <div id="discapacidad_fam" style="display: none;" >
<?php } ?>      
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_tip_discap_fam" class="col-sm-5 control-label keyupmce"><?= Yii::t("bienestar", "Type Disability") ?></label>
            <div class="col-sm-7">
                <?= Html::dropDownList("cmb_tip_discap_fam", $tipo_descapacidadfa, $tipo_discap_fam, ["class" => "form-control", "id" => "cmb_tip_discap_fam"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_por_discap" class="col-sm-5 control-label keyupmce"><?= Yii::t("bienestar", "Percentage Disability") ?></label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_por_discap" value="<?= $porcentajefadis ?>"  data-type="number" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Percentage Disability") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_tpare_dis_fam" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Kinship") ?></label>
            <div class="col-sm-7">
                <?= Html::dropDownList("cmb_tpare_dis_fam", $parentescofadisc, $tipparent_dis, ["class" => "form-control", "id" => "cmb_tpare_dis_fam"]) ?>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txth_doc_adj_dis" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
            <div class="col-sm-7">
                <?= Html::hiddenInput('txth_doc_adj_dis', $img_discapacidadfam, ['id' => 'txth_doc_adj_dis']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_doc_adj_dis',
                    'name' => 'txt_doc_adj_dis',                
                    'pluginLoading' => false,
                    'showMessage' => false,
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showCancel' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                        'browseLabel' => "Subir Archivo",
                        'uploadUrl' => Url::to(['/ficha/guardarficha']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                return {"upload_file": true, "name_file": "fam_discapacidad"};
            }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
            $('#txth_doc_adj_dis').val($('#txt_doc_adj_dis').val());
            $('#txt_doc_adj_dis').fileinput('upload');
        }",
                        "fileuploaderror" => "function (event, data, msg) {
            $('#txth_doc_adj_dis').val('');
            $(this).parent().parent().children().first().addClass('hide');
            //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
        }",
                        "filebatchuploadcomplete" => "function (event, files, extra) { 
            $(this).parent().parent().children().first().addClass('hide');
        }",
                        "filebatchuploadsuccess" => "function (event, data, previewId, index) {
            var form = data.form, files = data.files, extra = data.extra,
            response = data.response, reader = data.reader;
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
        }",
                        "fileuploaded" => "function (event, data, previewId, index) {
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
        }",
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <hr>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <label for="txt_enfermedad_fam" class="col-sm-5 control-label"><?= Yii::t("bienestar", "¿Do you have a family member with Catastrophic Illness?") ?></label>
        <div class="col-sm-7">
            <label>
                <input type="radio" name="signup-enfcf" id="signup-enfcf" value="1"  <?php
                if ($enfermedaden == 1) {
                    echo 'checked';
                }
                ?>> Si<br>
            </label>
            <label>
                <input type="radio" name="signup-enfcf_no" id="signup-enfcf_no" value="2"  <?php
                if ($enfermedaden == "") {
                    echo 'checked';
                }
                ?>> No<br>
            </label>
        </div> 
    </div>
</div>
<?php if ($enfermedaden ==1) { ?>
  <div id="enfermedad_fam" style="display: block;" >
<?php } else { ?>
  <div id="enfermedad_fam" style="display: none;" >
<?php } ?>         
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_tpare_enf_fam" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Kinship") ?></label>
            <div class="col-sm-7">
                <?= Html::dropDownList("cmb_tpare_enf_fam", $parentescoen, $tipparent_enf, ["class" => "form-control", "id" => "cmb_tpare_enf_fam"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txth_fadj_enf_fam" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
            <div class="col-sm-7">
                <?= Html::hiddenInput('txth_fadj_enf_fam', $img_enfermedadfam, ['id' => 'txth_fadj_enf_fam']); ?>
                <?php
                echo CFileInputAjax::widget([
                    'id' => 'txt_fadj_enf_fam',
                    'name' => 'txt_fadj_enf_fam',                
                    'pluginLoading' => false,
                    'showMessage' => false,
                    'pluginOptions' => [
                        'showPreview' => false,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showCancel' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="fa fa-folder-open"></i> ',
                        'browseLabel' => "Subir Archivo",
                        'uploadUrl' => Url::to(['/ficha/guardarficha']),
                        'maxFileSize' => Yii::$app->params["MaxFileSize"], // en Kbytes
                        'uploadExtraData' => 'javascript:function (previewId,index) {
                return {"upload_file": true, "name_file": "fam_catastrofica"};
            }',
                    ],
                    'pluginEvents' => [
                        "filebatchselected" => "function (event) {
            $('#txth_fadj_enf_fam').val($('#txt_fadj_enf_fam').val());
            $('#txt_fadj_enf_fam').fileinput('upload');
        }",
                        "fileuploaderror" => "function (event, data, msg) {
            $('#txth_fadj_enf_fam').val('');
            $(this).parent().parent().children().first().addClass('hide');
            //showAlert('NO_OK', 'error', {'wtmessage': objLang.Error_to_process_File__Try_again_, 'title': objLang.Error});   
        }",
                        "filebatchuploadcomplete" => "function (event, files, extra) { 
            $(this).parent().parent().children().first().addClass('hide');
        }",
                        "filebatchuploadsuccess" => "function (event, data, previewId, index) {
            var form = data.form, files = data.files, extra = data.extra,
            response = data.response, reader = data.reader;
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});  
        }",
                        "fileuploaded" => "function (event, data, previewId, index) {
            $(this).parent().parent().children().first().addClass('hide');
            var acciones = [{id: 'reloadpage', class: 'btn btn-primary', value: objLang.Accept, callback: 'reloadPage'}];
            //showAlert('OK', 'Success', {'wtmessage': objLang.File_uploaded_successfully__Do_you_refresh_the_web_page_, 'title': objLang.Success, 'acciones': acciones});                              
        }",
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-2">
        <a id="paso5backUpdate" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?></a>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">&nbsp;</div>    
    <div class="col-md-2">
        <a id="btn_save_1" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?> <span class="glyphicon glyphicon-floppy-disk"></span></a>
    </div>
</div>


