<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

?>
<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_empresa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= yii::t("formulario", 'Company') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_empresa", $model->emp_id, $arr_empresa, ["class" => "form-control", "id" => "cmb_empresa",]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_usuario" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= yii::t("formulario", 'Person') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_usuario", $model->usu_id, $arr_responsable, ["class" => "form-control", "id" => "cmb_usuario",]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_nivel" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("responsablesubunidad", 'Level') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_nivel", $model->niv_id, $arr_nivel, ["class" => "form-control", "id" => "cmb_nivel",]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_unidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", 'Unity Name') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_unidad", $model->ugpr_id, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad",]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_auditor" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("responsablesubunidad", 'Is Auditor') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_auditor", $model->runi_isadmin, $arr_auditor, ["class" => "form-control", "id" => "cmb_auditor"]) ?>  
                </div>
            </div>
        </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_status" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("responsablesubunidad", "Responsible Status")  ?></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_status" value="<?= $model->runi_estado ?>" data-type="number" placeholder="<?= gpr::t("responsablesubunidad", "Responsible Status")  ?>">
                    <span id="spanAccStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconAccStatus" class="<?= ($model->runi_estado == 1)?"glyphicon glyphicon-check":"glyphicon glyphicon-unchecked" ?>"></i></span>
                </div>
            </div>
        </div>
    </div> 
</form>

<input type="hidden" id="frm_id" value="<?= $model->runi_id ?>">