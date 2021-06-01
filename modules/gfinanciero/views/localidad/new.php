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
            <label for="cmb_pais" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("localidad", "Country") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?= Html::dropDownList("cmb_pais", "0", $arr_pais, ["class" => "form-control", "id" => "cmb_pais", ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_provincia" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("localidad", "State") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?= Html::dropDownList("cmb_provincia", "0", $arr_estado, ["class" => "form-control", "id" => "cmb_provincia", ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="cmb_ciudad" class="col-xs-5 col-sm-4 col-md-3 col-lg-2 control-label"><?= financiero::t("localidad", "City") ?></label>
            <div class="col-xs-7 col-sm-8 col-md-9 col-lg-10">
                <?= Html::dropDownList("cmb_ciudad", "0", $arr_ciudad, ["class" => "form-control", "id" => "cmb_ciudad", ]) ?>
            </div>
        </div>
    </div>
</form>