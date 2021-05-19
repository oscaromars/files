<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("rubro", "Heading Name") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->rub_nombre ?>" id="frm_name" data-type="alfa" placeholder="<?= financiero::t("rubro", "Heading Name") ?>">
            </div>
        </div>
    </div>
    
     <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_tipo" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("rubro", "Type of Heading") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
            <?= Html::dropDownList("cmb_tipo", $model->rub_tipo, $arr_tipo, ["class" => "form-control", "id" => "cmb_tipo", ]) ?> 
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="autocomplete-cuentaprincipal" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("rubro", "Main Account") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                SearchAutocomplete::widget([
                    'containerId' => 'cuentaprincipal',
                    'token' => $token,
                    'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/rubro/index',
                    'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                    'callback' => 'putCuentaPrincipalData',
                    'defaultValue' => $model->rub_cuenta_principal,
                    'htmlOptions' => ['class' => 'PBvalidation'],
                ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value="<?= $cprincipal ?>" id="frm_cuentaprincipaldesc" data-type="all" disabled="disabled" />
            </div>
        </div>

    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
       <div class="form-group">
            <label for="autocomplete-cuentaprovisional" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 control-label"><?= financiero::t("rubro", "Provisional Account") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <?=
                SearchAutocomplete::widget([
                    'containerId' => 'cuentaprovisional',
                    'token' => $token,
                    'url' => Url::base() . '/' . Yii::$app->controller->module->id . '/rubro/index',
                    'colDefault' => 0, // se coloca el numero de la columna por defecto a seleccionar
                    'callback' => 'putCuentaProvisionalData',
                    'defaultValue' => $model->rub_cuenta_provisional,
                    'htmlOptions' => ['class' => 'PBvalidation'],
                ]);
                ?>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <input type="text" class="form-control PBvalidations" value="<?= $cprovisional ?>" id="frm_cuentaprovisionaldesc" data-type="all" disabled="disabled" />
            </div>
        </div>
    </div> 
    
</form>
<input type="hidden" id="frm_id" value="<?= $model->rub_id ?>">

