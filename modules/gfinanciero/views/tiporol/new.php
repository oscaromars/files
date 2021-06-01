<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tiporol", "Role Name") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_name" data-type="all" placeholder="<?= financiero::t("tiporol", "Role Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_hour" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tiporol", "Hours") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="" id="frm_hour" data-type="number" placeholder="<?= financiero::t("tiporol", "Hours") ?>">
            </div>
        </div>
    </div>  
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_percentage" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("tiporol", "Percentage") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <div class="input-group">
                    <input type="text" class="form-control PBvalidation" value="" id="frm_percentage" data-type="number" data-lengthMin="1" data-lengthMax="3" placeholder="<?= financiero::t("tiporol", "Percentage") ?>">
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div>  
</form>