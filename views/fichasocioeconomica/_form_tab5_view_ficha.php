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
use kartik\date\DatePicker;
use yii\helpers\Url;
use app\components\CFileInputAjax;
?>


<div class="col-md-12">
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Additional") ?></span></h3>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="txt_discapacidad" class="col-sm-5 control-label"><?= Yii::t("bienestar", "¿Do you have any type of disability?") ?></label>
        <div class="col-sm-7">
            <label>
                <input type="radio" name="signup-dis" value="1" disabled="true"<?php if($discapacidadinte == 1) { echo 'checked';}?> > Si<br>                  
            </label>
            <label>
                <input type="radio" name="signup-dis" value="2" disabled="true" <?php if($discapacidadinte == "") { echo 'checked';}?>> No<br>       
               </label>
        </div> 
    </div>
</div>  
<div class="col-md-12">
    <div class="form-group">
        <label for="cmb_tip_discap" class="col-sm-5 control-label keyupmce"><?= Yii::t("bienestar", "Type Disability") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_tip_discap", $tipo_discapacidadint, $tipo_discap, ["class" => "form-control", "id" => "cmb_tip_discap", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="txt_por_discapacidad" class="col-sm-5 control-label keyupmce"><?= Yii::t("bienestar", "Percentage Disability") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_por_discapacidad" value="<?= $porcentajeint ?>" disabled="true" data-type="numerico" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Percentage Disability") ?>">
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="txth_doc_adj_disi" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
        <div><?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => $img_discapacidad]) . "' download='" . $imagen . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
            ?>  
        </div>
    </div>
</div>
<div class="col-md-12">
    <hr>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="txt_enfermedad" class="col-sm-5 control-label"><?= Yii::t("bienestar", "¿Do you have any catastrophic illness?") ?></label>
        <div class="col-sm-7">
            <label>
                <input type="radio" name="signup-enf" value="1" disabled="true" <?php if($enfermedadint == 1) { echo 'checked';}?>> Si<br>
            </label>
            <label>
                <input type="radio" name="signup-enf" value="2" disabled="true" <?php if($enfermedadint== "") { echo 'checked';}?>> No<br>
            </label>
        </div> 
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="txth_doc_adj_enfc" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
        <div><?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => $img_enfermedad]) . "' download='" . $imagen . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                ?>  
        </div>
    </div>
</div>
<div class="col-md-12">
    <hr>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="txt_discapacidad_fam" class="col-sm-5 control-label"><?= Yii::t("bienestar", "¿Do you have a family member with Severe Disability?") ?></label>
        <div class="col-sm-7">
            <label>
                <input type="radio" name="signup-discf" value="1"disabled="true"  <?php if($discapacidadfa == 1) { echo 'checked';}?>> Si<br>
                </label>
            <label>
                <input type="radio" name="signup-discf" value="2" disabled="true" <?php if($discapacidadfa == "") { echo 'checked';}?>> No<br>
            </label>
        </div> 
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="cmb_tip_discap_fam" class="col-sm-5 control-label keyupmce"><?= Yii::t("bienestar", "Type Disability") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_tip_discap_fam", $tipo_descapacidadfa, $tipo_discap_fam, ["class" => "form-control", "id" => "cmb_tip_discap_fam", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="txt_por_discap" class="col-sm-5 control-label keyupmce"><?= Yii::t("bienestar", "Percentage Disability") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_por_discap" value="<?= $porcentajefadis ?>" disabled="true" data-type="numerico" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Percentage Disability") ?>">
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="cmb_tpare_dis_fam" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Kinship") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_tpare_dis_fam", $parentescofadisc, $tipparent_dis, ["class" => "form-control", "id" => "cmb_tpare_dis_fam", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group">
        <label for="txth_doc_adj_dis" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
        <div><?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => $img_discapacidadfam]) . "' download='" . $imagen . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                ?>  
        </div>
    </div>
</div>
<div class="col-md-12">
    <hr>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="txt_enfermedad_fam" class="col-sm-5 control-label"><?= Yii::t("bienestar", "¿Do you have a family member with Catastrophic Illness?") ?></label>
        <div class="col-sm-7">
            <label>
                <input type="radio" name="signup-enfcf" value="1" disabled="true" <?php if($enfermedaden == 1) { echo 'checked';}?>> Si<br>
            </label>
            <label>
                <input type="radio" name="signup-enfcf" value="2" disabled="true" <?php if($enfermedaden == "") { echo 'checked';}?>> No<br>
            </label>
        </div> 
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="cmb_tpare_enf_fam" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Kinship") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_tpare_enf_fam", $parentescoen, $tipparent_enf, ["class" => "form-control", "id" => "cmb_tpare_enf_fam", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="txth_fadj_enf_fam" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Attach document") ?></label>
        <div><?php
                echo "<a href='" . Url::to(['/site/getimage', 'route' => $img_enfermedadfam]) . "' download='" . $imagen . "' ><span class='glyphicon glyphicon-download-alt'></span>Descargar Imagen</a>"
                ?>  
        </div>
    </div>
</div>
<div class="col-md-12"> 
    <div class="col-md-2">
        <a id="paso5backView" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?></a>
    </div>   
</div>