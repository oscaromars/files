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
            <label for="txt_buscarDataest" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control" value="" id="txt_buscarDataest" placeholder="<?= Yii::t("solicitud_ins", "Search by Dni or Names") ?> <?= academico::t("Especies", "Student") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_buscarprofesor" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Teacher") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control" value="" id="txt_buscarprofesor" placeholder="<?= academico::t("Academico", "Search by Teacher Name") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_unidad_dises" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_unidad_dises", 1, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad_dises", "disabled" => "True"]) ?>
            </div>
            <label for="cmb_modalidades" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidades", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidades"]) ?>
            </div>      
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_periodoes" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodoes", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodoes"]) ?>
            </div>            
            <label for="cmb_asignaturaes" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Subject") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_asignaturaes", 0, $arr_asignatura, ["class" => "form-control", "id" => "cmb_asignaturaes"]) ?>
            </div>                 
        </div>
    </div> 
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
        <label for="cmb_cursoreg" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Course") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_cursoreg", " ", $arr_curso, ["class" => "form-control", "id" => "cmb_cursoreg"]) ?>
            </div>        
            <label for="cmb_estadoes" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Status") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_estadoes", " ", $arr_estado, ["class" => "form-control", "id" => "cmb_estadoes"]) ?>
            </div> 
            <!-- <label for="cmb_jornadaes" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Working day") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                 Html::dropDownList("cmb_jornadaes", " ", $arr_jornada, ["class" => "form-control", "id" => "cmb_jornadaes"]) 
            </div>  --> 
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarData_estregsitro" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btnHabilitacurso" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?></a>
        </div>
    </div>    
</div>

