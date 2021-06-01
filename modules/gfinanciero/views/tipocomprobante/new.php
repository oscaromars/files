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
            <label for="frm_codigo" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipocomprobante", "Code") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_codigo" data-type="all" data-lengthMin="2" data-lengthMax="2" placeholder="<?= financiero::t("tipocomprobante", "Code") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_contador" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipocomprobante", "Accountant") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="0000000000" id="frm_contador" data-type="all" disabled="disabled" placeholder="<?= financiero::t("tipocomprobante", "Accountant") ?>">
            </div>
        </div>
    </div>  
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_nombre" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tipocomprobante", "Name") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_nombre" data-type="all" placeholder="<?= financiero::t("tipocomprobante", "Name") ?>">
            </div>
        </div>
    </div>  
</form>