<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use app\modules\academico\Module as academico;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_buscarDatapagopos" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control" value="" id="txt_buscarDatapagopos" placeholder="<?= Yii::t("solicitud_ins", "Search by Dni or Names") ?> <?= academico::t("Especies", "Student") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_buscarprofesorpos" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Teacher") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control" value="" id="txt_buscarprofesorpos" placeholder="<?= academico::t("Academico", "Search by Teacher Name") ?>">
            </div>
        </div>
    </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_promocion" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Promotion") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_promocion", 0, $arr_promocion, ["class" => "form-control", "id" => "cmb_promocion"]) ?>
            </div> 
            <label for="cmb_paralelopos" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Parallel") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_paralelopos", " ", $arr_paralelo, ["class" => "form-control", "id" => "cmb_paralelopos"]) ?>
            </div>   
        </div>
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_unidad_disespos" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_unidad_disespos", 2, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad_disespos", "disabled" => "true"]) ?>
            </div>
            <label for="cmb_modalidadespos" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidadespos", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidadespos"]) ?>
            </div>      
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">                                  
            <label for="cmb_asignaturaespos" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Subject") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_asignaturaespos", 0, $arr_asignatura, ["class" => "form-control", "id" => "cmb_asignaturaespos"]) ?>
            </div>  
            <label for="cmb_estadoespos" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Status") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_estadoespos", " ", $arr_estado, ["class" => "form-control", "id" => "cmb_estadoespos"]) ?>
            </div>             
        </div>
    </div>  
    
    
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-2 col-lg-2">                
            <a id="btn_buscarData_distpagopos" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
        <!--<div class="col-sm-2 col-md-2 col-xs-2 col-lg-2">                
            <a id="btnGuardarpagopos" href="javascript:" class="btn btn-primary btn-block"> <? Yii::t("formulario", "Save") ?></a>
        </div>-->
    </div>

</div>

