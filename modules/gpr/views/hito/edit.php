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
            <label for="frm_name" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", "Milestone Name") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="<?= $model->hito_nombre?>" id="frm_name" data-type="all" placeholder="<?= gpr::t("hito", "Milestone Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_desc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", 'Milestone Description') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="<?= $model->hito_descripcion ?>" id="frm_desc" data-type="all" placeholder="<?= gpr::t("hito", "Milestone Description") ?>">
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
                        'value' => date(Yii::$app->params["dateByDefault"], strtotime($model->hito_fecha_compromiso)),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_festimada", "data-type" => "fecha", "placeholder" => gpr::t("hito", "Deliver Date")],
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
            <label for="frm_peso" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", 'Weighing') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation" value="<?= $model->hito_peso ?>" id="frm_peso" data-type="number" placeholder="<?= gpr::t("hito", "Weighing") ?>">
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_presupuesto" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("hito", 'Budget') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation" value="<?= round($model->hito_presupuesto, 2) ?>" id="frm_presupuesto" data-type="number" placeholder="<?= gpr::t("hito", "Budget") ?>">
                    <span class="input-group-addon">$</span>
                </div>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_pro_id" value="<?= $model->pro_id ?>" />
<input type="hidden" id="frm_id" value="<?= $model->hito_id ?>" />