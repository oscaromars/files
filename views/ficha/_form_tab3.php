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
 * 
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
?>
<?= Html::hiddenInput('txth_ids', base64_decode($_GET['ids']), ['id' => 'txth_ids']); ?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Academic") ?></span></h3>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <h4><span id="lbl_Personeria"><?= Yii::t("formulario", "Middle Level Studies") ?></span></h4>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_inst_med" class="col-sm-5 control-label"><?= Yii::t("formulario", "Institution") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_inst_med" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Institution") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_tip_instaca_med" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Type Institution") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_tip_instaca_med", 0, $tip_instaca_med, ["class" => "form-control", "id" => "cmb_tip_instaca_med"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_pais_med" class="col-sm-5 control-label"><?= Yii::t("formulario", "Country") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_pais_med", 1, $paises_med, ["class" => "form-control", "id" => "cmb_pais_med"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_prov_med" class="col-sm-5 control-label"><?= Yii::t("formulario", "State") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_prov_med", 0, $provincias_med, ["class" => "form-control", "id" => "cmb_prov_med"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_ciu_med" class="col-sm-5 control-label"><?= Yii::t("formulario", "City") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_ciu_med", 0, $cantones_med, ["class" => "form-control", "id" => "cmb_ciu_med"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_titulo_med" class="col-sm-5 control-label"><?= Yii::t("formulario", "Title") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_titulo_med" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Title") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_grad_med" class="col-sm-5 control-label"><?= Yii::t("formulario", "Graduation Year") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_grad_med" data-type="graduacion" data-keydown="true" placeholder="<?= Yii::t("formulario", "Graduation Year") ?>">
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <h4><span id="lbl_Personeria"><?= Yii::t("formulario", "Third Level Studies") ?></span></h4>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_inst_ter" class="col-sm-5 control-label"><?= Yii::t("formulario", "Institution") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" data-required="false" id="txt_inst_ter" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Institution") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_tip_instaca_ter" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Type Institution") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_tip_instaca_ter", 0, $tip_instaca_ter, ["class" => "form-control", "id" => "cmb_tip_instaca_ter"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_pais_ter" class="col-sm-5 control-label"><?= Yii::t("formulario", "Country") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_pais_ter", 1, $paises_ter, ["class" => "form-control", "id" => "cmb_pais_ter"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_prov_ter" class="col-sm-5 control-label"><?= Yii::t("formulario", "State") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_prov_ter", 0, $provincias_ter, ["class" => "form-control", "id" => "cmb_prov_ter"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_ciu_ter" class="col-sm-5 control-label"><?= Yii::t("formulario", "City") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_ciu_ter", 0, $cantones_ter, ["class" => "form-control", "id" => "cmb_ciu_ter"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_titulo_ter" class="col-sm-5 control-label"><?= Yii::t("formulario", "Title") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" data-required="false" id="txt_titulo_ter" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Title") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_grad_ter" class="col-sm-5 control-label"><?= Yii::t("formulario", "Graduation Year") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false"  id="txt_grad_ter" data-type="graduacion" data-keydown="true" placeholder="<?= Yii::t("formulario", "Graduation Year") ?>">
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <h4><span id="lbl_Personeria"><?= Yii::t("formulario", "Fourth Level Studies") ?></span></h4>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_inst_cuat" class="col-sm-5 control-label"><?= Yii::t("formulario", "Institution") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" data-required="false" id="txt_inst_cuat" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Institution") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_tip_instaca_cuat" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Type Institution") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_tip_instaca_cuat", 0, $tip_instaca_cuat, ["class" => "form-control", "id" => "cmb_tip_instaca_cuat"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_pais_cuat" class="col-sm-5 control-label"><?= Yii::t("formulario", "Country") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_pais_cuat", 1, $paises_cuat, ["class" => "form-control", "id" => "cmb_pais_cuat"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_prov_cuat" class="col-sm-5 control-label"><?= Yii::t("formulario", "State") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_prov_cuat", 0, $provincias_cuat, ["class" => "form-control", "id" => "cmb_prov_cuat"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_ciu_cuat" class="col-sm-5 control-label"><?= Yii::t("formulario", "City") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_ciu_cuat", 0, $cantones_cuat, ["class" => "form-control", "id" => "cmb_ciu_cuat"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_titulo_cuat" class="col-sm-5 control-label"><?= Yii::t("formulario", "Title") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" data-required="false" id="txt_titulo_cuat" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Title") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_grad_cuat" class="col-sm-5 control-label"><?= Yii::t("formulario", "Graduation Year") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false" id="txt_grad_cuat" data-type="graduacion" data-keydown="true" placeholder="<?= Yii::t("formulario", "Graduation Year") ?>">
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <div class="col-md-2">
        <a id="paso3back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
    </div>
    <div class="col-md-8"></div>
    <div class="col-md-2">
        <a id="paso3next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
    </div>
</div>