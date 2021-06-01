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


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Family") ?></span></h3>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
    <div class="form-group">
        <label for="cmb_tip_instaca_medm" class="col-sm-4 control-label"><?= Yii::t("bienestar", "Level Instruction Mother") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_tip_instaca_medm", $instru_madre, $ninstruc_mad, ["class" => "form-control", "id" => "cmb_tip_instaca_medm", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
    <div class="form-group">
        <label for="cmb_tip_instaca_medp" class="col-sm-4 control-label"><?= Yii::t("bienestar", "Level Instruction Father") ?></label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_tip_instaca_medp", $instru_padre, $ninstruc_mad, ["class" => "form-control", "id" => "cmb_tip_instaca_medp", 'disabled' => "true"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
    <div class="form-group">
        <label for="txt_numeracion_mi" class="col-sm-4 control-label"><?= Yii::t("bienestar", "Number Family Members") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $miembro ?>" id="txt_numeracion_mi" disabled="true" data-type="numerico" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Number Family Members") ?>">
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
    <div class="form-group">
        <label for="txt_numeracion_sal" class="col-sm-4 control-label"><?= Yii::t("bienestar", "Salary Members") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?= $salario ?>" id="txt_numeracion_sal"disabled="true" data-type="numerico" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Salary Members") ?>">
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
    <div class="col-md-2">
        <a id="paso4backView" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
    </div>
    <div class="col-md-8"></div>
    <div class="col-md-2">
        <a id="paso4nextView" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
    </div>
</div>