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
            <label for="frm_codigo" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipocontribuyente", "Code") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_codigo" data-type="all" data-lengthMin="2" data-lengthMax="2" placeholder="<?= financiero::t("tipocontribuyente", "Code") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_nombre" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipocontribuyente", "Name") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_nombre" data-type="all" placeholder="<?= financiero::t("tipocontribuyente", "Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_fecha" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipocontribuyente", "Date") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?=
                DatePicker::widget([
                    'name' => 'frm_fecha',
                    'value' => date(Yii::$app->params["dateByDefault"]),
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control PBvalidation", "id" => "frm_fecha", "data-type" => "fecha", "placeholder" => financiero::t("tipocontribuyente", "Date")],
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
            <label for="frm_porrf" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipocontribuyente", "% Ret. RFE") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation" value="" id="frm_porrf" data-type="all" placeholder="<?= financiero::t("tipocontribuyente", "% Ret. RFE") ?>">
                <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div> 

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_porri" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipocontribuyente", "% Ret. Vat") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation" value="" id="frm_porri" data-type="all" placeholder="<?= financiero::t("tipocontribuyente", "% Ret. Vat") ?>">
                    <span class="input-group-addon">%</span>
                </div> 
            </div>
        </div>
    </div> 




    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_status" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipocontribuyente", "VAT Tax") ?></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_status" value="0" placeholder="<?= financiero::t("tipocontribuyente", "VAT Tax") ?>">
                    <span class="input-group-addon input-group-addon-border-left input-group-addon-pointer spanAccStatus"><i class="iconAccStatus glyphicon glyphicon-unchecked"></i></span>
                </div>
            </div>
        </div>
    </div> 



</form>