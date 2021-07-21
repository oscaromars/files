<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Datos Laborales") ?></span></h3>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_empresa" class="col-sm-5 control-label"><?= Yii::t("formulario", "Empresa, Centro o Institución") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false" id="txt_empresa" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nombre de la empresa a la que trabaja") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_cargo" class="col-sm-5 control-label"><?= Yii::t("formulario", "Cargo que desempeña") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false" id="txt_cargo" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Cargo que desempeña") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_cat_ocupacional" class="col-sm-5 control-label"><?= Yii::t("formulario", "Categoría Ocupacional") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false" id="txt_cat_ocupacional" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Categoría Ocupacional") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_direc_emp" class="col-sm-5 control-label"><?= Yii::t("formulario", "Dirección donde Labora") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false" id="txt_direc_emp" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Dirección donde Labora") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_telefono_emp" class="col-sm-5 control-label"><?= Yii::t("formulario", "Phone") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false" value="" id="txt_telefono_emp" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_correo_emp" class="col-sm-5 control-label"><?= Yii::t("formulario", "Correo Electrónico") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_correo_emp" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_fecha_ingreso" class="col-sm-5 control-label"><?= Yii::t("formulario", "Fecha de Ingreso") ?></label>
        <div class="col-sm-7">
            <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_ingreso',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_ingreso", "placeholder" => Yii::t("formulario", "Fecha de Ingreso")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
            ?>
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <div class="col-md-2">
        <a id="paso5back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
    </div>
    <div class="col-md-8"></div>
    <div class="col-md-2">
        <a id="paso5next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
    </div>
</div>