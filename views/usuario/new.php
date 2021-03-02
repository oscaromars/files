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
 * 
 */
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use yii\helpers\ArrayHelper;
?>

<form class="form-horizontal">
    <div class="row">
        <div class="col-md-6">
            <h3><?= Yii::t("perfil","Basic Information")?></h3><br />
            <div class="form-group">
                <label for="txt_nombres" class="col-sm-4 control-label"><?= Yii::t("persona", "First Name") ?></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" id="txt_nombres" value="" data-type="alfa" placeholder="<?= Yii::t("persona", "First Name") ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="txt_apellido" class="col-sm-4 control-label"><?= Yii::t("persona", "Last Name") ?></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" id="txt_apellido" value="" data-type="alfa" placeholder="<?= Yii::t("persona", "Last Name") ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="txt_cedula" class="col-sm-4 control-label"><?= Yii::t("formulario", "DNI") ?></label>
                <div class="col-sm-8">
                    <input type="text" maxlength="15" class="form-control keyupmce" value="" id="txt_cedula"   data-type="cedula" data-keydown="true" placeholder="<?= Yii::t("formulario", "National identity document") ?>">
                </div>
            </div>  
            <div class="form-group">
                <label for="cmb_genero" class="col-sm-4 control-label"><?= Yii::t("persona", "Gender") ?></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_genero", 0, \app\models\Utilities::genero(), ["class" => "form-control", "id" => "cmb_genero"]) ?>
                    
                </div>
            </div>
            <div class="form-group">
                <label for="dtp_per_fecha_nacimiento" class="col-sm-4 control-label"><?= Yii::t("persona", "Birth Date") ?></label>
                <div class="col-sm-8">
                    <?=
                    DatePicker::widget([
                        'id' => 'dtp_per_fecha_nacimiento',
                        'name' => 'dtp_per_fecha_nacimiento',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation","data-type" => "fecha", "placeholder" => Yii::t("persona", "Birth Date"),],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="txt_email" class="col-sm-4 control-label"><?= Yii::t("persona", "Email") ?></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" id="txt_email" value="" data-type="email" placeholder="<?= Yii::t("persona", "Email") ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="txt_celular" class="col-sm-4 control-label"><?= Yii::t("persona", "CellPhone") ?></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" id="txt_celular" data-type="all" value="" placeholder="<?= Yii::t("persona", "CellPhone") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h3><?= Yii::t("perfil","Account Information")?></h3><br />
            <div class="form-group">
                <label for="txt_username" class="col-sm-4 control-label"><?= Yii::t("login", "Username") ?></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" id="txt_username" data-type="all" placeholder="<?= Yii::t("login", "Username") ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="frm_clave" class="col-sm-4 control-label"><?= Yii::t("login", "Password") ?></label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <?= Html::passwordInput("frm_clave", "", ["class" => "form-control PBvalidation", "data-type" => "all", "id" => "frm_clave", "placeholder" => Yii::t("login", "Password") ]) ?>
                        <?= Html::tag('span', Html::button(Html::tag("i", "", ['class' => 'glyphicon glyphicon-eye-open']), ['id' => "view_pass_btn", 'class' => 'btn btn-primary btn-flat',]), ["class" => "input-group-btn", "data-toggle" => "tooltip", "data-placement" => "top", "title" => Yii::t("accion", "View")]) ?>
                        <?= Html::tag('span', Html::button(Html::tag("i", "", ['class' => 'fa fa-fw fa-key']), ['id' => "generate_btn", 'class' => 'btn btn-primary btn-flat',]), ["class" => "input-group-btn", "data-toggle" => "tooltip", "data-placement" => "top", "title" => Yii::t("passreset", "Generate")]) ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="frm_nueva_clave_repeat" class="col-sm-4 control-label"><?= Yii::t("login", "Confirm Password") ?></label>
                <div class="col-sm-8">
                    <input type="password" class="form-control PBvalidation" id="frm_nueva_clave_repeat" data-type="all" placeholder="<?= Yii::t("login", "Confirm Password") ?>">
                </div>
            </div>
            
        </div>
        <div class="col-md-6">
            <h3><?= Yii::t("usuario", "Add company role group") ?></h3><br />
            <div class="form-group">
                <label for="cmb_empresa" class="col-sm-4 control-label"><?= Yii::t("aplicacion", "Company") ?></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_empresa", 0, ArrayHelper::map(\app\models\Empresa::getAllEmpresa(), 'Ids', 'Nombre'), ["class" => "form-control", "id" => "cmb_empresa"]) ?>
                </div>
            </div>
            <div class="form-group">
                <label for="cmb_grupo" class="col-sm-4 control-label"><?= Yii::t("aplicacion", "Group") ?></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_grupo", 0, ArrayHelper::map(app\models\Grupo::getAllGrupos(), 'Ids', 'Nombre'), ["class" => "form-control", "id" => "cmb_grupo"]) ?>
                </div>
            </div>
            <div class="form-group">
                <label for="cmb_rol" class="col-sm-4 control-label"><?= Yii::t("aplicacion", "Role") ?></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_rol", 0, ArrayHelper::map(app\models\Rol::getAllRoles(), 'id', 'name'), ["class" => "form-control", "id" => "cmb_rol"]) ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4"></div>                    
                <div class="col-sm-4">
                    <?php /* Html::a('<span class="glyphicon glyphicon glyphicon-plus"></span> ' . Yii::t("accion", "Add").' '.Yii::t("formulario", "Company"), 
                                    ['/usuario/addempresa?popup=true', 'id' => 1], 
                                    ['id' => 'btn_addEmpresa','class' => 'pbpopup btn btn-primary btn-block']); */?>
                    
                    <?=Html::a('<span class="glyphicon glyphicon glyphicon-plus"></span> ' . Yii::t("accion", "Add").' '.Yii::t("formulario", "Company"), 
                            'javascript:',         
                            ['id' => 'btn_addEmpresa','class' => 'btn btn-primary btn-block']); ?>
                </div>
                <div class="col-sm-4"></div>   
            </div>
        </div>
            
    </div>
    
    <div class="col-sm-12">
        <div class="form-group">
            <div class="box-body table-responsive no-padding">
                <table  id="TbG_Empresas" class="table table-hover">
                    <thead>
                        <tr>
                            <th style="display:none; border:none;"><?= Yii::t("usuario", "Ids") ?></th>
                            <th style="display:none; border:none;"></th><!--eper_id-->
                            <th style="display:none; border:none;"><?= Yii::t("usuario", "Ids Company") ?></th>
                            <th><?= Yii::t("usuario", "Company") ?></th>
                            <th style="display:none; border:none;"><?= Yii::t("usuario", "Ids Role") ?></th>
                            <th><?= Yii::t("usuario", "Role") ?></th>
                            <th style="display:none; border:none;"><?= Yii::t("usuario", "Ids Group") ?></th>
                            <th><?= Yii::t("usuario", "Group") ?></th>
                            <!-- <th><?= Yii::t("usuario", "Detail") ?></th>-->
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-9"></div>   
        <div class="col-sm-3">
            <?=
            Html::a('<span class="glyphicon glyphicon-floppy-disk"></span> ' . Yii::t("accion", "Save"), 'javascript:', ['id' => 'btn_saveCreate', 'class' => 'btn btn-primary btn-block']);
            ?>              
        </div>
    </div>
    
</form>
<script>
    var AccionTipo='Create';
</script>