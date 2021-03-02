<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("umbral", "Threshold Name") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="<?= $model->umb_nombre ?>" id="frm_name" data-type="all" disabled="disabled" placeholder="<?= gpr::t("umbral", "Threshold Name") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_desc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("umbral", 'Threshold Description') ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="<?= $model->umb_descripcion ?>" id="frm_desc" data-type="all" disabled="disabled" placeholder="<?= gpr::t("umbral", "Threshold Description") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_ini" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("umbral", 'Threshold Start of Range') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <div class="col-sm-3 input-group">
                    <input type="text" id="frm_ini"  value="<?= $model->umb_per_inicio ?>" data-type="number" class="form-control PBvalidation" disabled="disabled" placeholder="<?= gpr::t("umbral", "Numeric value for Threshold") ?>">
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fin" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("umbral", 'Threshold End of Range') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <div class="col-sm-3 input-group">
                    <input type="text" id="frm_fin"  value="<?= $model->umb_per_fin ?>" data-type="number" class="form-control PBvalidation" disabled="disabled" placeholder="<?= gpr::t("umbral", "Numeric value for Threshold") ?>">
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_color" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("umbral", "Threshold Colour") ?><span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <div id="umbral-colorpickers" class="col-sm-3 input-group colorpicker-element">
                    <input type="text" class="form-control PBvalidation" id="frm_color" value="<?= $model->umb_color ?>" data-type="all" disabled="disabled" placeholder="<?= gpr::t("umbral", "Threshold Colour") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_bsc_status" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("umbral", "Threshold Status") ?></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_omod_status" value="<?= $model->umb_estado ?>" data-type="number" placeholder="<?= gpr::t("umbral", "Threshold Status") ?>">
                    <span id="spanModStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconModStatuss" class="<?= ($model->umb_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
                </div>
            </div>
        </div>
    </div> 
</form>
<input type="hidden" id="frm_id" value="<?= $model->umb_id ?>">