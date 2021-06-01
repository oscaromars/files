<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("categoria", "Category Name") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="" id="frm_name" data-type="all" placeholder="<?= gpr::t("categoria", "Category Name") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_desc" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("categoria", 'Category Description') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                <input type="text" class="form-control PBvalidation" value="" id="frm_desc" data-type="all" placeholder="<?= gpr::t("categoria", "Category Description") ?>">
            </div>
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_status" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= gpr::t("categoria", "Category Status") ?></label>
            <div class="col-sm-1">
                <div class="input-group">
                    <input type="hidden" class="form-control PBvalidation" id="frm_status" value="0" data-type="number" placeholder="<?= gpr::t("categoria", "Category Status") ?>">
                    <span id="spanAccStatus" class="input-group-addon input-group-addon-border-left input-group-addon-pointer"><i id="iconAccStatus" class="glyphicon glyphicon-unchecked"></i></span>
                </div>
            </div>
        </div>
    </div> 
</form>