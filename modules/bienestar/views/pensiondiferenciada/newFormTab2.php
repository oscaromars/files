<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;

use app\modules\bienestar\Module as Bienestar;

Bienestar::registerTranslations();

?>

<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_eaca" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Career") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_eaca", $eaca_id, $carreras, ["class" => "form-control", "id" => "cmb_eaca", "disabled" => "true"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_uaca" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Academic Unit") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_uaca", $uaca_id, $unidades, ["class" => "form-control", "id" => "cmb_uaca", "disabled" => "true"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_mod" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Modality") ?><span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_mod", $mod_id, $modalidades, ["class" => "form-control", "id" => "cmb_mod", "disabled" => "true"]) ?>
            </div>
        </div>
    </div>

    <?php foreach ($campos as $key => $value) { ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="<?= "cmp_" . $value['id'] ?>" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= $value['nombre'] ?> <span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <input type="text" class="form-control PBvalidation" id="<?= "cmp_" . $value['id'] ?>" data-type="all">
                </div>
            </div>
        </div>
    <?php } ?>
    
</form>