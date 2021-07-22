<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\bienestar\Module as Bienestar;

Bienestar::registerTranslations();

?>

<form class="form-horizontal">
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
    <?php foreach ($criterios as $key => $value) { ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="<?= "crt_" . $value['id'] ?>" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= $value['nombre'] ?><span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                    <?= Html::dropDownList("crt_" . $value['id'], 0, $value['combobox'], ["class" => "form-control", "id" => "crt_" . $value['id']]) ?>
                </div>
            </div>
        </div>
    <?php } ?>
    
</form>