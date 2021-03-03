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
            <label for="cmb_tipo" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("localidad", "Location Type") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?= Html::dropDownList("cmb_tipo", $model->C_I_OCG, $arr_tipo, ["class" => "form-control", "disabled" => "disabled", "id" => "cmb_tipo", ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="frm_name" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("localidad", "Item Name") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <input type="text" class="form-control PBvalidation" value="<?= $model->NOM_OCG ?>" id="frm_name" data-type="alfa" placeholder="<?= financiero::t("localidad", "Item Name") ?>">
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="frm_id" value="<?= $model->C_I_OCG ?>">
<input type="hidden" id="frm_cod" value="<?= $model->COD_OCG ?>">