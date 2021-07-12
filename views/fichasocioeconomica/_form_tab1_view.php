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

if (!empty($per_pasaporte)) {
    $tipodoc = "PASS";
} else {
    $tipodoc = "CED";
}
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Personal") ?></span></h3>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_primer_nombre" class="col-sm-5 control-label"><?= Yii::t("formulario", "First Name") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_pri_nombre ?>" id="txt_primer_nombre"  disabled ="true" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "First Name") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_segundo_nombre" class="col-sm-5 control-label"><?= Yii::t("formulario", "Middle Name") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_seg_nombre ?>" id="txt_segundo_nombre" disabled ="true" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Middle Name") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_primer_apellido" class="col-sm-5 control-label"><?= Yii::t("formulario", "Last Name") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_pri_apellido ?>" id="txt_primer_apellido" disabled ="true"  data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_segundo_apellido" class="col-sm-5 control-label"><?= Yii::t("formulario", "Last Second Name") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_seg_apellido ?>" id="txt_segundo_apellido" disabled ="true" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Second Name") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="cmb_tipo_dni" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Type DNI") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_tipo_dni", $tipodoc, $tipos_dni, ["class" => "form-control", "id" => "cmb_tipo_dni", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_cedula" class="col-sm-5 control-label"><?= Yii::t("formulario", "DNI") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_cedula ?>" id="txt_cedula" disabled ="true" data-type="cedula" data-keydown="true" placeholder="<?= Yii::t("formulario", "National identity document") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="cmb_genero" class="col-sm-5 control-label"><?= Yii::t("formulario", "Gender") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_genero", $per_genero, $genero, ["class" => "form-control", "id" => "cmb_genero", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="cmb_raza_etnica" class="col-sm-5 control-label"><?= Yii::t("formulario", "Ethnic") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_raza_etnica", $etn_id, $etnica, ["class" => "form-control", "id" => "cmb_raza_etnica", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_otra_etnia" class="col-sm-5 control-label"><?= Yii::t("formulario", "Ethnic Others") ?> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control keyupmce" value="<?= $oetn_nombre ?>" id="txt_otra_etnia" disabled ="true" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Ethnic Others") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_fecha_nacimiento" class="col-sm-5 control-label"><?= Yii::t("formulario", "Birth Date") ?></label>
        <div class="col-sm-7">
            <?=
            DatePicker::widget([
                'name' => 'txt_fecha_nacimiento',
                'value' => $per_fecha_nacimiento,
                'disabled' => true,
                'type' => DatePicker::TYPE_INPUT,
                'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_nacimiento", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Birth Date")],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => Yii::$app->params["dateByDatePicker"],
                ]]
            );
            ?>
        </div>
    </div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_nacionalidad" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nationality") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $per_nacionalidad ?>" id="txt_nacionalidad" disabled ="true" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nationality") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="cmb_pais_nac" class="col-sm-5 control-label"><?= Yii::t("formulario", "Country of birth") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_pais_nac", $pai_id_nacimiento, $paises_nac, ["class" => "form-control pai_combo", "id" => "cmb_pais_nac", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="cmb_prov_nac" class="col-sm-5 control-label"><?= Yii::t("formulario", "State of birth") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_prov_nac", $pro_id_nacimiento, $provincias_nac, ["class" => "form-control pro_combo", "id" => "cmb_prov_nac", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="cmb_ciu_nac" class="col-sm-5 control-label"><?= Yii::t("formulario", "City of birth") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_ciu_nac", $can_id_nacimiento, $cantones_nac, ["class" => "form-control can_combo", "id" => "cmb_ciu_nac", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_estado_civil" class="col-sm-5 control-label"><?= Yii::t("formulario", "Marital Status") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("txt_estado_civil", $eciv_id, $estado_civil, ["class" => "form-control", "id" => "txt_estado_civil", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_ftem_correo" class="col-sm-5 control-label"><?= Yii::t("formulario", "Email") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" value="<?= $per_correo ?>" id="txt_ftem_correo" disabled ="true" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_celular" class="col-sm-5 control-label"><?= Yii::t("formulario", "CellPhone") ?></label>
        <div class="col-sm-7">
            <div class="input-group">
                <span id="lbl_codeCountry" class="input-group-addon"><?= $area ?></span>
                <input type="text" class="form-control PBvalidation" value="<?= $per_celular ?>" id="txt_celular" disabled ="true" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
            </div>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="cmb_tipo_sangre" class="col-sm-5 control-label"><?= Yii::t("formulario", "Blood Type") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_tipo_sangre", $tsan_id, $tipos_sangre, ["class" => "form-control", "id" => "cmb_tipo_sangre", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="cmb_ciu_nac" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nac. Ecuatoriano") ?></label>
        <div class="col-sm-7">
            <label>
                <input type="radio" name="signup-ecu" value="1" disabled="true" <?php
                if ($per_nac_ecuatoriano == 1) {
                    echo 'checked';
                }
                ?>> Si<br>
            </label>
            <label>
                <input type="radio" name="signup-ecu" value="0" disabled="true" <?php
                if ($per_nac_ecuatoriano == 0) {
                    echo 'checked';
                }
                ?>> No<br>
            </label>            
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Contact Information") ?></span></h3>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_nombres_contacto" class="col-sm-5 control-label"><?= Yii::t("formulario", "First Names") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $con_nombre ?>" id="txt_nombres_contacto" disabled ="true" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "First Names") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_apellidos_contacto" class="col-sm-5 control-label"><?= Yii::t("formulario", "Last Names") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce"  value="<?= $con_nombre ?>" id="txt_apellidos_contacto" disabled ="true" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Names") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_telefono_con" class="col-sm-5 control-label"><?= Yii::t("formulario", "Phone") ?></label>
        <div class="col-sm-7">
            <div class="input-group">
                <span id="lbl_codeCountrycon" class="input-group-addon"><?= $area ?></span>
                <input type="text" class="form-control PBvalidation" value="<?= $con_telefono ?>" id="txt_telefono_con" disabled ="true" data-type="telefono"  data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
            </div>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_celular_con" class="col-sm-5 control-label"><?= Yii::t("formulario", "CellPhone") ?></label>
        <div class="col-sm-7">
            <div class="input-group">
                <span id="lbl_codeCountrycell" class="input-group-addon"><?= $area ?></span>
                <input type="text" class="form-control PBvalidation" value="<?= $con_celular ?>" id="txt_celular_con" disabled ="true" data-type="celular" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
            </div>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="txt_address_con" class="col-sm-5 control-label"><?= Yii::t("formulario", "Address") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $con_direccion ?>" id="txt_address_con" disabled ="true" data-type="alfa"  data-keydown="true" placeholder="<?= Yii::t("formulario", "Address") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">  
    <div class="form-group">
        <label for="cmb_parentesco_con" class="col-sm-5 control-label"><?= Yii::t("formulario", "Kinship") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_parentesco_con", $con_parentesco, $tipparent_dis, ["class" => "form-control", "id" => "cmb_parentesco_con", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
    <div class="col-md-10"></div>
    <div class="col-md-2">
        <a id="paso1nextView" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
    </div>
</div>