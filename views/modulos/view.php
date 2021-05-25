<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Aplicacion;
?>

<form class="form-horizontal">
    <div class="form-group">
        <label for="frm_modulo" class="col-sm-3 control-label"><?= Yii::t("modulo", "Module Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_modulo" value="<?= $model->mod_nombre ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("modulo", "Module Name") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_aplicacion" class="col-sm-3 control-label"><?= Yii::t("aplicacion", "Application Name") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_aplicacion" value="<?= Aplicacion::findOne($model->apl_id)->apl_nombre ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("aplicacion", "Application Name") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_type" class="col-sm-3 control-label"><?= Yii::t("modulo", "Type of Module") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_mod_type" value="<?= $model->mod_tipo ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("modulo", "Type of Module") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_image" class="col-sm-3 control-label"><?= Yii::t("modulo", "Image") ?></label>
        <div class="col-sm-9">
            <div class="input-group">
                <input type="text" class="form-control PBvalidation" id="frm_mod_image" value="<?= $model->mod_dir_imagen ?>" data_alias="<?= $model->mod_dir_imagen ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("modulo", "Image") ?>">
                <span class="input-group-addon"><i id="iconMod" class="<?= $model->mod_dir_imagen ?>"></i></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_url" class="col-sm-3 control-label"><?= Yii::t("modulo", "Url Module") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_mod_url" value="<?= $model->mod_url ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("modulo", "Url Module") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_orden" class="col-sm-3 control-label"><?= Yii::t("modulo", "Position Module") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_mod_orden" value="<?= $model->mod_orden ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("modulo", "Position Module") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_lang" class="col-sm-3 control-label"><?= Yii::t("modulo", "Language File") ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control PBvalidation" id="frm_mod_lang" value="<?= $model->mod_lang_file ?>" data-type="alfa" disabled="disabled" placeholder="<?= Yii::t("modulo", "Language File") ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="frm_mod_status" class="col-sm-3 control-label"><?= Yii::t("modulo", "Status Module") ?></label>
        <div class="col-sm-1">
            <div class="input-group">
                <input type="hidden" class="form-control PBvalidation" id="frm_mod_status" value="<?= $model->mod_estado ?>" data-type="number" placeholder="<?= Yii::t("modulo", "Status Module") ?>">
                <span id="spanModStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconModStatuss" class="<?= ($model->mod_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_mod_id" value="<?= $model->mod_id ?>">