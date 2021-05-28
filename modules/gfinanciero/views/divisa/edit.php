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
            <label for="frm_codigo" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("divisa", "Code") ?> <span class="text-danger">*</span> </label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->COD_DIV ?>" id="frm_codigo" data-type="all" disabled="disabled" placeholder="<?= financiero::t("divisa", "Code") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_nombre" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("divisa", "Name") ?> <span class="text-danger">*</span> </label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->NOM_DIV ?>" id="frm_nombre" data-type="alfa" placeholder="<?= financiero::t("divisa", "Name") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_cotizacion" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("divisa", "Quotation") ?> <span class="text-danger">*</span> </label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->V_COTIZ ?>" id="frm_cotizacion" data-type="all" placeholder="<?= financiero::t("divisa", "Quotation") ?>">
            </div>
        </div>
    </div>
</form>

<input type="hidden" id="frm_id" value="<?= $model->COD_DIV ?>">

