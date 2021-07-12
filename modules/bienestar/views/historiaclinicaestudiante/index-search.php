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

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="form-group">
        <label for="txt_buscarData" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Yii::t("formulario", "Search") ?></label>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <input type="text" class="form-control" value="" id="txt_buscarData" placeholder="<?= Yii::t("solicitud_ins", "Search by Dni or Names") ?>">
        </div>
    </div>
</div>    
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    <div class="form-group">            
        <label for="cmb_unidad_dis_asignacion" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <?= Html::dropDownList("cmb_unidad_dis", 0,  $arr_unidad , ["class" => "form-control", "id" => "cmb_unidad_dis_asignacion"]) ?>
        </div>
           
    </div>
</div>  
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    <div class="form-group">  
        <label for="cmb_modalidad" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Yii::t("formulario", "Mode") ?></label>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <?= Html::dropDownList("cmb_modalidad", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
        </div>   
    </div>
</div>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    <div class="form-group">            
        <label for="cmb_periodo_asignacion" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Yii::t("formulario", "Period") ?></label>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <?= Html::dropDownList("cmb_periodo", 0,  $arr_periodo , ["class" => "form-control", "id" => "cmb_periodo_asignacion"]) ?>
        </div>
    </div>                                            
</div>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
    <div class="form-group">                        
        <label for="cmb_jornadaes" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= academico::t("Academico", "Working day") ?></label>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <?= Html::dropDownList("cmb_jornada", " ", $arr_jornada, ["class" => "form-control", "id" => "cmb_jornada"]) ?>
        </div>   
    </div>
</div> 
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="form-group">      
        <label for="cmb_materia" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Yii::t("formulario", "Subject") ?></label>
         <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
            <?= Html::dropDownList("cmb_materia", 0,  $arr_materias, ["class" => "", "id" => "cmb_materia"]) ?>
        </div>
    </div>                                            
</div>    
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="form-group" style="display: flex;justify-content: center;">   
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" style="margin: 0 auto;">          
            <a id="btn_buscarData_dist" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div>


