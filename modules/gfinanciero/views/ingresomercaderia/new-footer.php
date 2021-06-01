<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gfinanciero\Module as financiero;
use yii\data\ArrayDataProvider;

financiero::registerTranslations();

?>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="frm_bul" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Bulk Boxes") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                <input type="text" class="form-control PBvalidation" value="" id="frm_bul" disabled="disabled" data-type="all" placeholder="<?= financiero::t("ingresomercaderia", "Bulk Boxes") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_recibido" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Received By") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                <input type="text" class="form-control PBvalidation" value="" id="frm_recibido" disabled="disabled" data-type="all" placeholder="<?= financiero::t("ingresomercaderia", "Received By") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_revisado" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Reviewed By") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                <input type="text" class="form-control PBvalidation" value="" id="frm_revisado" disabled="disabled" data-type="all" placeholder="<?= financiero::t("ingresomercaderia", "Reviewed By") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_kardex" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Annotated Kardex") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                <input type="text" class="form-control PBvalidation" value="" id="frm_kardex" disabled="disabled" data-type="all" placeholder="<?= financiero::t("ingresomercaderia", "Annotated Kardex") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="txta_obse" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Observations") ?> <span class="text-danger">*</span></label>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                <textarea class="form-control PBvalidation" id="txta_obse" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Observations") ?>"></textarea>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="frm_tart" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Article Types") ?></label>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                <input type="text" class="form-control PBvalidations" value="0" id="frm_tart" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Article Types") ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="frm_citem" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Items Amount") ?></label>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                <input type="text" class="form-control PBvalidations" value="0" id="frm_citem" data-type="all" disabled="disabled" placeholder="<?= financiero::t("ingresomercaderia", "Items Amount") ?>">
            </div>
        </div>
        <div class="form-group" style="font-size: 40px;">
            <label for="frm_citem" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><?= financiero::t("ingresomercaderia", "Total") ?></label>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                <label class="control-label text-dark"><?= Yii::$app->params['currency'] ?></label>
                <label class="control-label text-dark" id="lbl_ttotal">0.00</label>
                <input type="hidden" id="frm_ttotal" value="0.00" />
            </div>
        </div>
    </div>
</div>