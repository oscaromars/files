<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gpr\Module as gpr;
use kartik\date\DatePicker;

gpr::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("proyecto", "Project Name") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="<?= $model->pro_nombre ?>" id="frm_name" disabled="disabled" data-type="all" placeholder="<?= gpr::t("proyecto", "Project Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_desc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("proyecto", 'Project Description') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <textarea class="form-control PBvalidation" id="frm_desc" disabled="disabled" data-type="all" placeholder="<?= gpr::t("proyecto", "Project Description") ?>"><?= $model->pro_descripcion ?></textarea>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_tipo" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("tipoproyecto", "Project Type") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_tipo", $model->tpro_id, $arr_tippro, ["class" => "form-control", "id" => "cmb_tipo", "disabled" => "disabled",]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_objop" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("objetivooperativo", "Operative Objective") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_objop", $model->oope_id, $arr_objope, ["class" => "form-control", "id" => "cmb_objop", "disabled" => "disabled",]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_ugpr" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("unidad", "Unity") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_ugpr", $model->ugpr_id, $arr_unidad, ["class" => "form-control", "id" => "cmb_ugpr", "disabled" => "disabled",]) ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_presupuesto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", 'Budget') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->pro_presupuesto ?>" id="frm_presupuesto" disabled="disabled" data-type="number" placeholder="<?= gpr::t("hito", "Budget") ?>">
                    <span class="input-group-addon">$</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fini" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("proyecto", "Initial Date") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_fini',
                        'value' => date(Yii::$app->params["dateByDefault"], strtotime($model->pro_fecha_inicio)),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fini", "disabled" => "disabled", "data-type" => "fecha", "placeholder" => gpr::t("proyecto", "Initial Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fend" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("proyecto", "End Date") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_fend',
                        'value' => date(Yii::$app->params["dateByDefault"], strtotime($model->pro_fecha_fin)),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fend", "disabled" => "disabled", "data-type" => "fecha", "placeholder" => gpr::t("proyecto", "End Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_restricciones" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("proyecto", 'Restrictions') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <textarea class="form-control PBvalidation" id="frm_restricciones" disabled="disabled" data-type="all" placeholder="<?= gpr::t("proyecto", "Restrictions") ?>"><?= $model->pro_restricciones ?></textarea>
            </div>
        </div>
    </div>
</form>

<input type="hidden" id="frm_id" value="<?= $model->pro_id ?>">
