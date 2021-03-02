<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Aplicacion;
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_modulo" class="col-sm-3 control-label"><?= Yii::t("modulo", "Module Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_modulo" value="" data-type="alfa" placeholder="<?= Yii::t("modulo", "Module Name") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="cmb_aplicacion" class="col-sm-3 control-label"><?= Yii::t("aplicacion", "Application Name") ?></label>
        <div class="col-sm-9">
            <?= Html::dropDownList("cmb_aplicacion", "", $arr_aplications, ["class" => "form-control", "id" => "cmb_aplicacion"]) ?>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_type" class="col-sm-3 control-label"><?= Yii::t("modulo", "Type of Module") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_mod_type" value="" data-type="all" placeholder="<?= Yii::t("modulo", "Type of Module") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_image" class="col-sm-3 control-label"><?= Yii::t("modulo", "Image") ?></label>
        <div class="col-sm-9">
            <div class="input-group">
                <input type="text" class="form-control PBvalidation" id="frm_mod_image" value="" data-alias="" data-type="all" placeholder="<?= Yii::t("modulo", "Image") ?>">
                <span class="input-group-addon"><i id="iconMod" class=""></i></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_url" class="col-sm-3 control-label"><?= Yii::t("modulo", "Url Module") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_mod_url" value="" data-type="all" placeholder="<?= Yii::t("modulo", "Url Module") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_orden" class="col-sm-3 control-label"><?= Yii::t("modulo", "Position Module") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_mod_orden" value="" data-type="number" placeholder="<?= Yii::t("modulo", "Position Module") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_lang" class="col-sm-3 control-label"><?= Yii::t("modulo", "Language File") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_mod_lang" value="" data-type="all" placeholder="<?= Yii::t("modulo", "Language File") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_status" class="col-sm-3 control-label"><?= Yii::t("modulo", "Status Module") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_mod_status" value="0" data-type="number" placeholder="<?= Yii::t("modulo", "Status Module") ?>">
                <span id="spanModStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconModStatus" class="glyphicon glyphicon-unchecked"></i></span>
            </div>
        </div>
    </div>
</form>
