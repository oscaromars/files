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
            <label for="cmb_periodo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodo", 0,  $arr_periodo , ["class" => "form-control", "id" => "cmb_periodo"]) ?>
            </div>                              

            <label for="cmb_estado" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Status") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_estado", 0,  $arrEstados , ["class" => "form-control", "id" => "cmb_estado"]) ?>
            </div> 
        </div>
        </div>    

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
        <div class="form-group">     
            <label for="cmb_tipo_asignacion" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Tipo Asignación") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_tipo_asignacion", 0,  $arr_tipo_distributivo , ["class" => "form-control", "id" => "cmb_tipo_asignacion"]) ?>
            </div>                 
        </div>    
        </div>    
        
         
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarData_dist" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div>

