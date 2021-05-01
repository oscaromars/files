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
            <label for="txt_buscarprofesorasi" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Teacher") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control" value="" id="txt_buscarprofesorasi" placeholder="<?= academico::t("Academico", "Search by Teacher Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_unidad_disesasi" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_unidad_disesasi", 1, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad_disesasi", "disabled" => "True"]) ?>
            </div>
            <label for="cmb_modalidadesasi" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidadesasi", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidadesasi"]) ?>
            </div>      
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_periodoesasi" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodoesasi", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodoesasi"]) ?>
            </div>            
           <!-- <label for="cmb_asignaturaesasi" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Subject") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                < Html::dropDownList("cmb_asignaturaesasi", 0, $arr_asignatura, ["class" => "form-control", "id" => "cmb_asignaturaesasi"]) ?>
            </div> -->  
            <label for="cmb_cursoasi" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Course") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <!-- < Html::dropDownList("cmb_estadoes", " ", $arr_estado, ["class" => "form-control", "id" => "cmb_estadoes"]) ?>-->
                <?= Html::dropDownList("cmb_cursoasi", " ", $arr_curso, ["class" => "form-control", "id" => "cmb_cursoasi"]) ?>
            </div>                 
        </div>
    </div> 
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_estasi" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Status") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
               <?= Html::dropDownList("cmb_estasi", " ", $arr_estado, ["class" => "form-control", "id" => "cmb_estasi"]) ?>               
            </div>             
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarData_estasignar" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btnAsignacurso" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?></a>
        </div>
    </div>
    
</div>

