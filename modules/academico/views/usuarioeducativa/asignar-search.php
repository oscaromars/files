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
            <label for="txt_buscarData" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control" value="" id="txt_buscarData" placeholder="<?= Yii::t("solicitud_ins", "Search by Dni or Names") ?>">
            </div>
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_unidad_dise" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_unidad_dise", 1,  $arr_unidad , ["class" => "form-control", "id" => "cmb_unidad_dise", "Disabled" => "true"]) ?>
            </div>
            <label for="cmb_modalidad_dise" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidad_dise", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad_dise"]) ?>
            </div>      
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_periodo_dise" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodo_dise", 0,  $arr_periodo , ["class" => "form-control", "id" => "cmb_periodo_dise"]) ?>
            </div>       
            <label for="cmb_materia_dise" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Subject") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_materia_dise", 0,  $arr_materias, ["class" => "form-control", "id" => "cmb_materia_dise"]) ?>
            </div> 
        </div>                                            
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">                        
            <label for="cmb_jornada_dise" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Working day") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_jornada_dise", " ", $arr_jornada, ["class" => "form-control", "id" => "cmb_jornada_dise"]) ?>
            </div>   
        </div>
    </div> 
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarData_dise" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div>

