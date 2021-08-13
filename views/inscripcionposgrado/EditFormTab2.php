<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use app\widgets\PbGridView\PbGridView;
use yii\helpers\Url;
use app\models\Utilities;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;

?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_titulo_3erNivelEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Título de Tercer Nivel") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_titulo_3erNivelEdit" value="<?= $instruccion_model->eins_titulo3ernivel ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Título de Tercer Nivel") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_universidad1Edit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Universidad Tercer Nivel") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_universidad1Edit" value="<?= $instruccion_model->eins_institucion3ernivel ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Universidad Tercer Nivel") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_año_grado1Edit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Año de Graduación Tercer Nivel") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_año_grado1Edit" value="<?= $instruccion_model->eins_añogrado3ernivel ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Año de Graduación Tercer Nivel") ?>">
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_titulo_4toNivelEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Título de Cuarto Nivel") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_titulo_4toNivelEdit" value="<?= $instruccion_model->eins_titulo4tonivel ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Título de Cuarto Nivel") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_universidad2Edit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Universidad Cuarto Nivel") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_universidad2Edit" value="<?= $instruccion_model->eins_institucion4tonivel ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Universidad Cuarto Nivel") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_año_grado2Edit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Año de Graduación Cuarto Nivel") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_año_grado2Edit" value="<?= $instruccion_model->eins_añogrado4tonivel ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Año de Graduación Cuarto Nivel") ?>">
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_empresaEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Empresa, Centro o Institución") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_empresaEdit" value="<?= $laboral_model->ilab_empresa ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Empresa, Centro o Institución") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_cargoEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Cargo que desempeña") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_cargoEdit" value="<?= $laboral_model->ilab_cargo ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Cargo que desempeña") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_telefono_empEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Télefono de la empresa") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_telefono_empEdit" value="<?= $laboral_model->ilab_telefono_emp ?>" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Télefono de la empresa") ?>">
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_provincia_empEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Provincia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_provincia_empEdit", $laboral_model->ilab_prov_emp, $arr_prov_emp, ["class" => "form-control", "id" => "cmb_provincia_empEdit"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_ciudad_empEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Cantón") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("cmb_ciudad_empEdit", $laboral_model->ilab_ciu_emp, $arr_ciu_emp, ["class" => "form-control", "id" => "cmb_ciudad_empEdit"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_parroquiaEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Parroquia") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_parroquiaEdit" value="<?= $laboral_model->ilab_parroquia ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Parroquia") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_direc_empEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Dirección donde Labora") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_direc_empEdit" value="<?= $laboral_model->ilab_direccion_emp ?>" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Dirección donde Labora") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_añoingreso_empEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Año de Ingreso") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_añoingreso_empEdit" value="<?= $laboral_model->ilab_añoingreso_emp ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Año de Ingreso") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_correo_empEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Correo de la Empresa") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_correo_empEdit" value="<?= $laboral_model->ilab_correo_emp ?>" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Correo de la Empresa") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_cat_ocupacionalEdit" class="col-lg-3 col-md-3 col-xs-3 col-sm-3 control-label"><?= Yii::t("inscripcionposgrado", "Categoría Ocupacional") ?></label>
            <div class="col-lg-9 col-md-9 col-xs-9 col-sm-9">
                <?= Html::dropDownList("txt_cat_ocupacionalEdit", $laboral_model->ilab_cat_ocupacional, $arr_categoria, ["class" => "form-control", "id" => "txt_cat_ocupacionalEdit"]) ?>
            </div>
        </div>
    </div>

</form>