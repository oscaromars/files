<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

?>
<div class="col-sm-8">
    <form class="form-horizontal">
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_empresa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= yii::t("formulario", 'Company') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_empresa", 0, $arr_empresa, ["class" => "form-control", "id" => "cmb_empresa"]) ?>  
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_usuario" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= yii::t("formulario", 'Person') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_usuario", 0, $arr_responsable, ["class" => "form-control", "id" => "cmb_usuario"]) ?>  
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_nivel" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("responsablesubunidad", 'Level') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_nivel", 0, $arr_nivel, ["class" => "form-control", "id" => "cmb_nivel"]) ?>  
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_unidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", 'Unity Name') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_unidad", 0, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad"]) ?>  
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="cmb_auditor" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("responsablesubunidad", 'Is Auditor') ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?= Html::dropDownList("cmb_auditor", 0, $arr_auditor, ["class" => "form-control", "id" => "cmb_auditor"]) ?>  
                </div>
            </div>
        </div> 
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div class="form-group">
                <label for="frm_status" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("responsablesubunidad", "Responsible Status") ?></label>
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="hidden" class="form-control PBvalidation" id="frm_status" value="0" data-type="number" placeholder="<?= gpr::t("responsablesubunidad", "Responsible Status") ?>">
                        <span id="spanAccStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconAccStatus" class="glyphicon glyphicon-unchecked"></i></span>
                    </div>
                </div>
            </div>
        </div> 
    </form>
</div>