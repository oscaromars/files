<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Financiamiento") ?></span></h3>
</div><br><br></br>

<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">   
    <div class="form-group">
        <label for="txt_tipo_financiamiento" class="col-sm-5 control-label"><?= Yii::t("formulario", "Tipo de Financiamiento") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-6">
            <?= Html::dropDownList("txt_tipo_financiamiento", "", $tipo_financiamiento, ["class" => "form-control", "id" => "txt_tipo_financiamiento"]) ?>
        </div>
    </div>
</div><br><br></br>

<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">   
    <div class="form-group">
        <label for="txt_inst_credito" class="col-sm-5 control-label"><?= Yii::t("formulario", "Institución de Crédito") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-6">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_inst_credito" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Institución de Crédito") ?>">
        </div>
    </div><br></br>
</div><br></br>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <div class="col-md-2">
        <a id="paso3back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"> &nbsp;</div>
    <div class="col-md-2">
        <a id="paso3next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
    </div>
</div>