<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\vendor\penblu\searchautocomplete\SearchAutocomplete;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();
?>

<form class="form-horizontal">
    <div class="row">        
        <div class="col-md-6">
            <div class="form-group">
                <label for="cmb_tipo_est" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("establecimiento", "Establishment") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?= Html::dropDownList("cmb_tipo_est", "0", $arr_establecimiento, ["class" => "form-control", "id" => "cmb_tipo_est",]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_codigo" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("puntoemision", "Emission Point Code") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="" id="frm_codigo" data-type="all" data-lengthMin="3" data-lengthMax="3" placeholder="<?= financiero::t("puntoemision", "Emission Point Code") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_nombre" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("puntoemision", "Name") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="" id="frm_nombre" data-type="all"  placeholder="<?= financiero::t("puntoemision", "Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_fecha" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("puntoemision", "Date") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <?=
                    DatePicker::widget([
                        'name' => 'frm_fecha',
                        'value' => date(Yii::$app->params["dateByDefault"]),
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation", "id" => "frm_fecha", "data-type" => "fecha", "placeholder" => financiero::t("puntoemision", "Date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>  
                </div>
            </div>
        </div>
    </div>

    <div class ="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_ubicacion" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("puntoemision", "Location") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="" id="frm_ubicacion" data-type="all" placeholder="<?= financiero::t("puntoemision", "Location") ?>">  
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                 <label for="frm_status" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("puntoemision", "Authorization") ?></label>
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="hidden" class="form-control PBvalidation" id="frm_status" value="0" placeholder="<?= financiero::t("puntoemision", "Authorization") ?>">
                        <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus"><i class="iconAccStatus glyphicon glyphicon-unchecked"></i></span>
                    </div>
                </div>
            </div>
        </div>           
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="frm_responsable" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("puntoemision", "Responsable") ?> <span class="text-danger">*</span></label>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    <input type="text" class="form-control PBvalidation" value="" id="frm_responsable" data-type="all" placeholder="<?= financiero::t("puntoemision", "Responsable") ?>">  
                </div>
            </div>
        </div>
        <div class="col-md-6">

        </div>
    </div>

</form>

