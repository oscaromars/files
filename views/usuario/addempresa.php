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

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>

<div class="col-md-12">
    <div class="row">
        <div class="form-group">
            <label for="cmb_empresa" class="col-sm-4 control-label"><?= Yii::t("aplicacion", "Company") ?></label>
            <div class="col-sm-8">
                <?= Html::dropDownList("cmb_empresa", 0, ArrayHelper::map(\app\models\Empresa::getAllEmpresa(), 'Ids', 'Nombre'), ["class" => "form-control", "id" => "cmb_empresa"]) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="cmb_grupo" class="col-sm-4 control-label"><?= Yii::t("aplicacion", "Group") ?></label>
            <div class="col-sm-8">
                <?= Html::dropDownList("cmb_grupo", 0, ArrayHelper::map(app\models\Grupo::getAllGrupos(), 'Ids', 'Nombre'), ["class" => "form-control", "id" => "cmb_grupo"]) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="cmb_rol" class="col-sm-4 control-label"><?= Yii::t("aplicacion", "Role") ?></label>
            <div class="col-sm-8">
                <?= Html::dropDownList("cmb_rol", 0, ArrayHelper::map(app\models\Rol::getAllRoles(), 'id', 'name'), ["class" => "form-control", "id" => "cmb_rol"]) ?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <p><?= Html::a('<span class="glyphicon glyphicon-floppy-disk"></span> ' . Yii::t("accion", "Save"), 'javascript:', ['id' => 'btn_AddItem', 'class' => 'btn btn-primary btn-block']); ?> </p>
        </div>
        <div class="col-sm-4">
    </div>
</div>