<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\helpers\Url;
use app\components\CFileInputAjax;

?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Becas") ?></span></h3>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_tipo_beca" class="col-sm-5 control-label"><?= Yii::t("formulario", "Tipo de Beca") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false" id="txt_tipo_beca" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Tipo de Beca") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_mot_beca" class="col-sm-5 control-label"><?= Yii::t("formulario", "Motivo de la Beca") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false" id="txt_mot_beca" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Motivo de la Beca") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_monto_recibido" class="col-sm-5 control-label"><?= Yii::t("formulario", "Monto Recibido") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false" id="txt_monto_recibido" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Monto Recibido de becas") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_porcentajes" class="col-sm-5 control-label"><?= Yii::t("formulario", "Porcentajes") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation" data-required="false" id="txt_porcentajes" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Porcentajes") ?>">
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <div class="col-md-2">
        <a id="paso6back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?></a>
    </div>
    <div class="col-md-8">&nbsp;</div>
    <div class="col-md-2">
        <a id="btn_save_1" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?> <span class="glyphicon glyphicon-floppy-disk"></span></a>
    </div>
</div>


