<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author root
 */
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
$this->title = 'Modificar Usuario ';
?>
<?= Html::hiddenInput('txth_usu_id',$model[0]["usu_id"],['id' =>'txth_usu_id']); ?>
<?= Html::hiddenInput('txth_per_id',$model[0]["per_id"],['id' =>'txth_per_id']); ?>
<?= Html::hiddenInput('txth_eper_id',$model[0]["eper_id"],['id' =>'txth_eper_id']); ?>

<form class="form-horizontal">
    <div class="row">
        <div class="col-md-6">
            <h3><?= Yii::t("perfil","Basic Information")?></h3><br />
            <div class="form-group">
                <label for="txt_nombres" class="col-sm-4 control-label"><?= Yii::t("persona", "First Name") ?></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" id="txt_nombres" value="<?= $model[0]["per_pri_nombre"] ?>" data-type="alfa" placeholder="<?= Yii::t("persona", "First Name") ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="txt_apellido" class="col-sm-4 control-label"><?= Yii::t("persona", "Last Name") ?></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" id="txt_apellido" value="<?= $model[0]["per_pri_apellido"] ?>" data-type="alfa" placeholder="<?= Yii::t("persona", "Last Name") ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="txt_cedula" class="col-sm-4 control-label"><?= Yii::t("formulario", "DNI") ?></label>
                <div class="col-sm-8">
                    <input type="text" maxlength="15" class="form-control keyupmce" value="<?= $model[0]["per_cedula"] ?>" id="txt_cedula"   data-type="cedula" data-keydown="true" placeholder="<?= Yii::t("formulario", "National identity document") ?>">
                </div>
            </div> 
            <div class="form-group">
                <label for="cmb_genero" class="col-sm-4 control-label"><?= Yii::t("persona", "Gender") ?></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_genero", $model[0]["per_genero"], \app\models\Utilities::genero(), ["class" => "form-control", "id" => "cmb_genero"]) ?>
                    
                </div>
            </div>
            <div class="form-group">
                <label for="frm_nacimiento" class="col-sm-4 control-label"><?= Yii::t("persona", "Birth Date") ?></label>
                <div class="col-sm-8">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_nacimiento',
                        'value' => $model[0]["per_fecha_nacimiento"] ,
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_nacimiento", "value" => $persona->per_fecha_nacimiento,"data-type" => "fecha", "placeholder" => Yii::t("persona", "Birth Date"),],
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
                    <input type="text" class="form-control PBvalidation" id="txt_email" value="<?= $model[0]["per_correo"] ?>" data-type="email" placeholder="<?= Yii::t("persona", "Email") ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="txt_celular" class="col-sm-4 control-label"><?= Yii::t("persona", "CellPhone") ?></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" id="txt_celular" data-type="all" value="<?= $model[0]["per_celular"] ?>" placeholder="<?= Yii::t("persona", "CellPhone") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h3><?= Yii::t("perfil","Account Information")?></h3><br />
            <div class="form-group">
                <label for="txt_username" class="col-sm-4 control-label"><?= Yii::t("login", "Username") ?></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" id="txt_username" value="<?= $model[0]["usu_user"] ?>" data-type="all" placeholder="<?= Yii::t("login", "Username") ?>">
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
                    <?=Html::a('<span class="glyphicon glyphicon glyphicon-plus"></span> ' . Yii::t("accion", "Add").' '.Yii::t("formulario", "Company"), 
                            'javascript:',         
                            ['id' => 'btn_addEmpresa','class' => 'btn btn-primary btn-block']); ?>
                </div>
                <div class="col-sm-4">                           
                </div>
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
        <div class="form-group">
            <div class="col-sm-4"></div>                    
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <?=
                Html::a('<span class="glyphicon glyphicon-floppy-disk"></span> ' . Yii::t("accion", "Save"), 'javascript:', ['id' => 'btn_saveUpdate', 'class' => 'btn btn-primary btn-block']);
                ?>                

            </div>
        </div>
    </div>  
</form>

<script>
    //Datos de Usuario GrupoRol
    var AccionTipo='Update';
    var varEgrData=<?= $usuEGR ?>;
</script>