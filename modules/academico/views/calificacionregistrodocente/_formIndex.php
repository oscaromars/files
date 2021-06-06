<?php

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;

Academico::registerTranslations();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="form-group">                 
            <label for="cmb_periodo_clfc" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Period") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">  
                    <?= Html::dropDownList("cmb_periodo_clfc",1,$arr_periodoActual, ["class" => "form-control", "id" => "cmb_periodo_clfc"]) ?>              
            </div>
             <label for="cmb_profesor_clfc" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Teacher") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_profesor_clfc", 0,$arr_profesor_all, ["class" => "form-control", "id" => "cmb_profesor_clfc"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group"> 
            <label for="cmb_modalidad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Modality") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_modalidad", 1,$arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
            </div>

             <label for="cmb_materiabus" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Course") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_materiabus", 5,$arr_asignatura,["class" => "form-control", "id" => "cmb_materiabus"]) ?>
            </div>

           
        </div>
    </div>
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group"> 
              <label for="cmb_unidad_bus" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Academic unit") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::dropDownList("cmb_unidad_bus", 1, $arr_ninteres,["class" => "form-control", "id" => "cmb_unidad_bus"]) ?>
            </div>
            
        

        </div>


    </div>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarDataestClfcns" href="javascript:" class="btn btn-primary btn-block"> <?= academico::t("Academico", "Search") ?></a>
        </div>
          <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
          <br>
        </div>
         <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_download_acta" href="javascript:" class="btn btn-primary btn-block"> <?= academico::t("Academico", "Acta de Calificaciones") ?></a>
        </div>
    </div>
</div>

