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
?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_tipo_discapEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Tipo de Discapacidad") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_tipo_discapEdit", $discapacidad_model->tdis_id, $arr_tip_discap, ["class" => "form-control", "id" => "cmb_tipo_discapEdit"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_porc_discapacidadEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("bienestar", "Percentage Disability") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_porc_discapacidadEdit" value="<?= $discapacidad_model->ides_porcentaje ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Percentage Disability") ?>">
            </div>
        </div><br><br></br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_año_docenciaEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Años de Docencia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_año_docenciaEdit" value="<?= $docencia_model->ides_año_docencia ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Años de Docencia") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_area_docenciaEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Área de Docencia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_area_docenciaEdit" value="<?= $docencia_model->ides_area_docencia ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Área de Docencia") ?>">
            </div>
        </div><br><br></br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_articulosEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Número de Árticulos Publicados") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_articulosEdit" value="<?= $investigaciones_model->iein_articulos_investigacion ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Número de Árticulos Publicados") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_area_investigacionEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Área de Investigación") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_area_investigacionEdit" value="<?= $investigaciones_model->iein_area_investigacion ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Área de Investigación") ?>">
            </div>
        </div><br><br></br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_financiamientoEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Tipo de Financiamiento") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_financiamientoEdit" value="<?= $ipos_model->ipos_tipo_financiamiento ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Tipo de Financiamiento") ?>">
            </div>
        </div>
    </div>

</form>