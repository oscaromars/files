<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gpr\Module as gpr;
use kartik\date\DatePicker;

gpr::registerTranslations();

?>
<?php if(!$error): ?>
<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", "Milestone Name") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="<?= $modelHito->hito_nombre?>" disabled="disabled" id="frm_name" data-type="all" placeholder="<?= gpr::t("hito", "Milestone Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_festimada" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", "Deliver Date") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_festimada',
                        'value' => date(Yii::$app->params["dateByDefault"], strtotime($modelHito->hito_fecha_compromiso)),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_festimada", "disabled" => "disabled", "data-type" => "fecha", "placeholder" => gpr::t("hito", "Deliver Date")],
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
            <label for="frm_freal" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", "Actual Date") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?=
                    DatePicker::widget([
                        'name' => 'frm_festimada',
                        'value' => (isset($model->hseg_fecha_real) && $model->hseg_fecha_real != "")?(date(Yii::$app->params["dateByDefault"], strtotime($model->hseg_fecha_real))):"",
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_freal", "disabled" => "disabled", "data-type" => "fecha", "placeholder" => gpr::t("hito", "Actual Date")],
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
            <label for="frm_presupuesto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", 'Budget') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation" value="<?= round($modelHito->hito_presupuesto, 2) ?>" disabled="disabled" id="frm_presupuesto" data-type="number" placeholder="<?= gpr::t("hito", "Budget") ?>">
                    <span class="input-group-addon">$</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_gasto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", 'Current Cost') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation" value="<?= round($model->hseg_presupuesto, 2) ?>" disabled="disabled" id="frm_gasto" data-type="number" placeholder="<?= gpr::t("hito", "Current Cost") ?>">
                    <span class="input-group-addon">$</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_avance" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", 'Progress') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->hseg_progreso ?>" disabled="disabled" id="frm_avance" data-type="number" placeholder="<?= gpr::t("hito", "Progress") ?>">
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_cumplido" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", "Milestone Accomplished") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <?= Html::dropDownList("cmb_obj", $modelHito->hito_cumplido, $arr_cumplimiento, ["class" => "form-control", "id" => "cmb_cumplido", "disabled" => "disabled", ]) ?>  
            </div>
        </div>
    </div> 
</form>
<?php endif; ?>
<input type="hidden" id="frm_id" value="<?= $model->hseg_id ?>" />
<input type="hidden" id="frm_hito_id" value="<?= $modelHito->hito_id ?>" />
<input type="hidden" id="frm_pro_id" value="<?= $modelHito->pro_id ?>" />
