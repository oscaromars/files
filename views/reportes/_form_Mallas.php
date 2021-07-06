<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\MallaAcademica;
//use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\UsuarioSearch */
/* @var $form yii\widgets\ActiveForm */


$mod_unidad = new UnidadAcademica();
$arr_unidad = $mod_unidad->consultarUnidadAcademicasxUteg();
$unidad = ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_unidad), "id", "name");



?>
<div class="reportemallas-search">

<form class="form-horizontal">   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_unidad" class="col-sm-4 col-lg-4 col-md-4 col-xs-4 control-label"><?= Yii::t("formulario", "Unidad Académica:") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_unidad", 0, $unidad, ["class" => "form-control pro_combo", "id" => "cmb_unidad"]) ?>
            </div>
        </div>
    </div>
    
   

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_modalidad" class="col-sm-4 col-lg-4 col-md-4 col-xs-4 control-label"><?= Yii::t("formulario", "Modalidad:") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidad", 0, $arr_modalidad, ["class" => "form-control pro_combo", "id" => "cmb_modalidad"]) ?>
            </div>
        </div>
    </div>


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_carrera" class="col-sm-4 col-lg-4 col-md-4 col-xs-4 control-label"><?= Yii::t("formulario", "Carrera:") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_carrera", 0, $carrera, ["class" => "form-control pro_combo", "id" => "cmb_carrera"]) ?>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_malla" class="col-sm-4 col-lg-4 col-md-4 col-xs-4 control-label"><?= Yii::t("formulario", "Malla Académica:") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_malla", 0, $mallaca, ["class" => "form-control pro_combo", "id" => "cmb_malla"]) ?>
            </div>
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-offset-4">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary', 'id' => "btn_buscarMallas"]) ?>
        </div>   
    </div>


</form>
</div>