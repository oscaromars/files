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
            <label for="frm_id" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("subcentro", "Sub Center Code") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->COD_SCEN ?>" id="frm_id" data-type="all" disabled="disabled" placeholder="<?= financiero::t("subcentro", "Sub Center Code") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("subcentro", "Sub Center Name") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->NOM_SCEN ?>" id="frm_name" data-type="all" disabled="disabled" placeholder="<?= financiero::t("subcentro", "Sub Center Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_tipo" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("centro", "Center Name") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
            <?= Html::dropDownList("cmb_tipo", $model->COD_CEN, $arr_tipo, ["class" => "form-control", "id" => "cmb_tipo", "disabled" => "disabled", ]) ?>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_id" value="<?= $model->COD_SCEN ?>">
