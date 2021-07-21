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

?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_carrera" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 control-label"><?= Bienestar::t("pensiondiferenciada", "Last Name") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control PBvalidation" id="cmb_carrera" data-type="all" placeholder="<?= Bienestar::t("pensiondiferenciada", "Last Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_carrera" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Academico::t("profesor", "Country") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_carrera", $persona_model->pai_id_domicilio, $arr_pais, ["class" => "form-control", "id" => "cmb_carrera", "disabled" => "true"]) ?>
            </div>
        </div>
    </div>
</form>