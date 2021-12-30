<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_idioma1" class="col-sm-3 control-label"><?= Yii::t("formulario", "Idioma 1") ?> <span class="text-danger">*</span> </label>
            <div class="col-lg-9">
                <?= Html::dropDownList("cmb_idioma1", $idioma_model->idi_id, $arr_idioma, ["class" => "form-control", "id" => "cmb_idioma1"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_nivelidioma1" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nivel Idioma") ?> <span class="text-danger">*</span> </label>
            <div class="col-lg-9">
                <?= Html::dropDownList("cmb_nivelidioma1", $idioma_model->nidi_id, $arr_nivelidioma, ["class" => "form-control", "id" => "cmb_nivelidioma1"]) ?>
            </div>
        </div>
    </div>
  <?php   if ($idioma_modelf != Null) {  ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_idioma2" class="col-sm-3 control-label"><?= Yii::t("formulario", "Idioma 2") ?></label>
            <div class="col-lg-9">
                <?= Html::dropDownList("cmb_idioma2", 2, $arr_idioma2, ["class" => "form-control", "id" => "cmb_idioma2"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group" id="Dividiomas">
            <label for="cmb_nivelidioma2" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nivel Idioma") ?></label>
            <div class="col-lg-9">
                <?= Html::dropDownList("cmb_nivelidioma2", $idioma_model->nidi_id, $arr_nivelidioma2, ["class" => "form-control", "id" => "cmb_nivelidioma2"]) ?>
            </div>
        </div>
    </div>
      <?php  } ?>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group" style="display: none;" id="Divotroidioma">
            <label for="txt_nombreidioma" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nombre del Idioma") ?> <span class="text-danger">*</span></label>
            <div class="col-lg-9">
                <input type="text" maxlength="10" class="form-control keyupmce" id="txt_nombreidioma" value="<?= $idioma_model->eidi_nombre_idioma ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nombre del Idioma") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div style="display: none;" id="Divotronivelidioma">
            <label for="cmb_nivelotroidioma" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nivel Idioma") ?> <span class="text-danger">*</span> </label>
            <div class="col-lg-9">
                <?= Html::dropDownList("cmb_nivelotroidioma", $idioma_model->nidi_id, $arr_nivelidioma, ["class" => "form-control", "id" => "cmb_nivelotroidioma"]) ?>
            </div>
        </div>
    </div>
</form>