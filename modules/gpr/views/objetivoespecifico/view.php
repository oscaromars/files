<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("objetivoespecifico", "Specific Objective Name") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="<?= $model->oesp_nombre ?>" id="frm_name" data-type="all" disabled="disabled" placeholder="<?= gpr::t("objetivoespecifico", "Specific Objective Name") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_obj" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("objetivoestrategico", "Strategic Objective Name") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_obj", $model->oest_id, $arr_objestr, ["class" => "form-control", "id" => "cmb_obj", "disabled" => "disabled", ]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_adm" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", "Unity Name") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_adm", $model->ugpr_id, $arr_unidades, ["class" => "form-control", "id" => "cmb_adm", "disabled" => "disabled", ]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_desc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("objetivoespecifico", 'Specific Objective Description') ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <textarea class="form-control PBvalidation" id="frm_desc" rows="5" data-type="all" disabled="disabled" placeholder="<?= gpr::t("objetivoespecifico", "Specific Objective Description") ?>"><?= $model->oesp_descripcion ?></textarea>
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_bsc_status" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("objetivoespecifico", "Specific Objective Status") ?></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_omod_status" value="<?= $model->oesp_estado ?>" data-type="number" placeholder="<?= gpr::t("objetivoespecifico", "Specific Objective Status") ?>">
                    <span id="spanModStatuss" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconModStatuss" class="<?= ($model->oesp_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
                </div>
            </div>
        </div>
    </div> 
</form>
<input type="hidden" id="frm_id" value="<?= $model->oesp_id ?>">