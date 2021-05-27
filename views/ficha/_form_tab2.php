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
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Address") ?></span></h3>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_pais_dom" class="col-sm-5 control-label"><?= Yii::t("formulario", "Country") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <?php //echo Html::dropDownList("cmb_pais_dom", $pai_id_domicilio, $paises_dom, ["class" => "form-control", "id" => "cmb_pais_dom"]) ?>
            <select id="cmb_pais_dom" name="cmb_pais_dom" class="form-control pai_combo">
                <?php
                $code = "";
                foreach ($paises_nac as $key => $value) {

                    $selected = ($pai_id_nacimiento == $value['id']) ? "selected='seleted'" : "";
                    if ($selected != "")
                        $code = "+" . preg_replace('/\s+/', '', $value['code']);
                    echo "<option value='" . $value['id'] . "' data-code='" . "+" . preg_replace('/\s+/', '', $value['code']) . "' $selected >" . $value['value'] . "</option>";
                }
                ?> 
            </select>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_prov_dom" class="col-sm-5 control-label"><?= Yii::t("formulario", "State") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_prov_dom", $pro_id_domicilio, $provincias_dom, ["class" => "form-control", "id" => "cmb_prov_dom"]) ?>
        </div>
    </div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_ciu_dom" class="col-sm-5 control-label"><?= Yii::t("formulario", "City") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_ciu_dom", $can_id_domicilio, $cantones_dom, ["class" => "form-control", "id" => "cmb_ciu_dom"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_telefono_dom" class="col-sm-5 control-label"><?= Yii::t("formulario", "Phone") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <div class="input-group">
                <span id="lbl_codeCountrydom" class="input-group-addon"><?= $code ?></span>
                <input type="text" class="form-control PBvalidation" value="<?= $per_domicilio_telefono ?>" id="txt_telefono_dom" data-type="telefono_sin" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
            </div>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_sector_dom" class="col-sm-5 control-label"><?= Yii::t("formulario", "Sector") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $sector ?>" id="txt_sector_dom" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Sector") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_cprincipal_dom" class="col-sm-5 control-label"><?= Yii::t("formulario", "Main Street") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_domicilio_cpri ?>" id="txt_cprincipal_dom" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Main Street") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_csecundaria_dom" class="col-sm-5 control-label"><?= Yii::t("formulario", "High Street") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $secundaria ?>" id="txt_csecundaria_dom" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "High Street") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_numeracion_dom" class="col-sm-5 control-label"><?= Yii::t("formulario", "Numeration") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false" value="<?= $per_domicilio_num ?>" id="txt_numeracion_dom" data-type="numeracion" data-keydown="true" placeholder="<?= Yii::t("formulario", "Numeration") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_referencia_dom" class="col-sm-5 control-label"><?= Yii::t("formulario", "Reference") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_domicilio_ref ?>" id="txt_referencia_dom" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Reference") ?>">
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <div class="col-md-2">
        <a id="paso2back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"> &nbsp;</div>
    <div class="col-md-2">
        <a id="paso2next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
    </div>
</div>