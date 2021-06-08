<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\academico\Module as academico;

$leyendarc = '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
          <div class="form-group">
          <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
          <div style = "width: 533px;" class="alert alert-info"><span style="font-weight: bold"> Nota: </span> Si no encuentra la unidad en el listado, presione botón Insertar Estudiantes.</div>
          </div>
          </div>
          </div>';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php  echo $leyendarc;?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_periodo_educ" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodo_educ", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo_educ"]) ?>
            </div> 
            <label for="cmb_modalidad_educ" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidad_educ", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad_educ"]) ?>
            </div>      
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
        <label for="cmb_aula_educ" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Course") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_aula_educ", 0, $arr_aula, ["class" => "form-control", "id" => "cmb_aula_educ"]) ?>
            </div>        
            <label for="cmb_unidad_educ" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Unidad Educativa") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_unidad_educ", 0, $arr_unidadeduc, ["class" => "form-control", "id" => "cmb_unidad_educ"]) ?>
            </div>          
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group"> 
            <label for="cmb_evaluacion_educ" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Evaluation") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_evaluacion_educ", 0, $arr_evaluacion, ["class" => "form-control", "id" => "cmb_evaluacion_educ"]) ?>
            </div>            
        </div>
    </div> 

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarData_educativa" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
        <!-- <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btnHabilitacurso" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?></a>
        </div>-->
    </div>    
</div>

