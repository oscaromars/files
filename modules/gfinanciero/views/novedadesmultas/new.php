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
                        'defaultValue' => NULL,
                        'htmlOptions' => ['class' => 'PBvalidation'],
                    ]);
                ?>
            </div>
            <div class="col-xs-3 col-sm-4 col-md-4 col-lg-5">
                <input type="text" class="form-control PBvalidations" value="" id="frm_empleadodesc" data-type="all" disabled="disabled" />
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_valor" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("novedadesmultas", "Value") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <div class="input-group">
                    <span class="input-group-addon"><?= Yii::$app->params['currency'] ?></span>
                    <input type="text" class="form-control PBvalidation" value="0.00" id="frm_valor" data-type="all" placeholder="<?= financiero::t("novedadesmultas", "Value") ?>">
                </div>
            </div>
        </div>
    </div>  
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_observacion" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("novedadesmultas", "Observation") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <textarea class="form-control PBvalidations" rows="4" id="frm_observacion" data-type="all" placeholder="<?= financiero::t("novedadesmultas", "Observation") ?>"></textarea>
            </div>
        </div>
    </div>  
</form>