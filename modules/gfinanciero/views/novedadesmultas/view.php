<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

$token = SearchAutocomplete::getToken();
?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="autocomplete-empleado" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("empleado", "Employee") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-4 col-sm-4 col-md-5 col-lg-5">
                <?=
                    SearchAutocomplete::widget([
                        'containerId' => 'empleado',
                        'token' => $token,
                        'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/novedadesmultas/index',
                        'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                        'callback' => 'putEmpleadoData',
                        'defaultValue' => $code,
                        'htmlOptions' => ['class' => 'PBvalidation', 'disabled' => 'disabled'],
                    ]);
                ?>
            </div>
            <div class="col-xs-3 col-sm-4 col-md-4 col-lg-5">
                <input type="text" class="form-control PBvalidations" value="<?= $nombres ?>" id="frm_empleadodesc" data-type="all" disabled="disabled" />
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_valor" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("novedadesmultas", "Value") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidation" disabled="disabled" value="<?= number_format($model->nmul_valor, 2, '.', ',') ?>" id="frm_valor" data-type="all" placeholder="<?= financiero::t("novedadesmultas", "Value") ?>">
                </div>
            </div>
        </div>
    </div>  
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_observacion" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("novedadesmultas", "Observation") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <textarea class="form-control PBvalidations" rows="4" id="frm_observacion" disabled="disabled" data-type="all" placeholder="<?= financiero::t("novedadesmultas", "Observation") ?>"><?= $model->nmul_observacion ?></textarea>
            </div>
        </div>
    </div>  
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_status" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("novedadesmultas", "Status") ?><span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?= Html::dropDownList("cmb_status", $model->nmul_estado_cancelado, $arr_estados, ["class" => "form-control", "id" => "cmb_status", "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 ccEst" style="<?=  (($model->nmul_estado_cancelado == "1")?"":"display: none;") ?>">
        <div class="form-group">
            <label for="cmb_autoriza" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("novedadesmultas", "User Authorize") ?><span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?= Html::dropDownList("cmb_autoriza", $autoriza, $arr_Empleados, ["class" => "form-control", "id" => "cmb_autoriza", "disabled" => "disabled"]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 ccEst" style="<?=  (($model->nmul_estado_cancelado == "1")?"":"display: none;") ?>">
        <div class="form-group">
            <label for="frm_fpago" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("novedadesmultas", "Payment Date") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
            <?=
                DatePicker::widget([
                    'name' => 'frm_fpago',
                    'value' => (isset($model->nmul_fecha_pago))?(date(Yii::$app->params["dateByDefault"], strtotime($model->nmul_fecha_pago))):'',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidation", "id" => "frm_fpago", "data-type" => "fecha", "placeholder" => financiero::t("novedadesmultas", "Payment Date"), "disabled" => "disabled"],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>  
            </div>
        </div>
    </div>
    <input type="hidden" id="frm_id" value="<?= $model->nmul_id ?>" />
</form>