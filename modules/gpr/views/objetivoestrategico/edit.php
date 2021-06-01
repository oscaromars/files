<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("objetivoestrategico", "Strategic Objective Name") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="<?=  $model->oest_nombre ?>" id="frm_name" data-type="all" placeholder="<?= gpr::t("objetivoestrategico", "Strategic Objective Name") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_enf" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("enfoque", 'Focus') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_enf", $model->enf_id, $arr_enfoque, ["class" => "form-control", "id" => "cmb_enf"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_bsc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("categoriabsc", 'Category BSC') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_bsc", $model->cbsc_id, $arr_bsc, ["class" => "form-control", "id" => "cmb_bsc"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_plan" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("planificacionpedi", 'Pedi Planning') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_plan", $model->pped_id, $arr_plan, ["class" => "form-control", "id" => "cmb_plan"]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_desc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("objetivoestrategico", "Strategic Objective Description") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <textarea id="frm_desc" class="form-control PBvalidation" rows="5" data-type="all" placeholder="<?= gpr::t("objetivoestrategico", "Strategic Objective Description") ?>"><?=  $model->oest_descripcion ?></textarea>        
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fupd" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("objetivoestrategico", "Strategic Objective Last Update Date") ?></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidations" value="<?= date(Yii::$app->params["dateByDefault"], strtotime($model->oest_fecha_actualizacion)) ?>" id="frm_fupd" data-type="alfa" disabled="disabled" placeholder="<?= gpr::t("objetivoestrategico", "Strategic Objective Name") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_status" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("objetivoestrategico", "Strategic Objective Status") ?></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_status" value="<?= $model->oest_estado ?>" data-type="number" placeholder="<?= gpr::t("objetivoestrategico", "Strategic Objective Status") ?>">
                    <span id="spanAccStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconAccStatus" class="<?= ($model->oest_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
                </div>
            </div>
        </div>
    </div> 
</form>
<input type="hidden" id="frm_id" value="<?= $model->oest_id ?>">