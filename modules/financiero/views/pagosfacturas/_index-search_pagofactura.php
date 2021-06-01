<?php

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\ArrayHelper;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;


financiero::registerTranslations();
admision::registerTranslations();
academico::registerTranslations();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_buscarDataEstudiantepag" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                <input type="text" class="form-control" value="" id="txt_buscarDataEstudiantepag" placeholder="<?= Yii::t("formulario", "Search by Names") ." o ". Yii::t("formulario", "Identification document") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="lbl_inicio" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Start date") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DateTimePicker::widget([
                    'name' => 'txt_fecha_inipag',
                    'options' => ["class" => "form-control", "id" => "txt_fecha_inipag", "placeholder" => Yii::t("formulario", "Start date")],
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'format' => 'yyyy-MM-dd hh:mm ',
                        // 'startDate' => '01-Mar-2014 12:00 AM',
                        'todayHighlight' => true
                                    ]]
                                );
                ?>   
              <div>                   
              <!--<input type="time" style="width:80px;float:right" class="form-control" value="" id="hora" name="hora" />--> 
              </div>
              </div>  
                         
              <label for="lbl_fin" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "End date") ?></label>
              <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                /*DatePicker::widget([
                    'name' => 'txt_fecha_finpag',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_finpag", "placeholder" => Yii::t("formulario", "End date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );*/
                DateTimePicker::widget([
                    'name' => 'txt_fecha_finpag',
                    'options' => ["class" => "form-control", "id" => "txt_fecha_finpag", "placeholder" => Yii::t("formulario", "End date")],
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'format' => 'yyyy-MM-dd hh:mm ',                        
                        'todayHighlight' => true
                                    ]]
                                );
                ?>
            </div>
          </div>
          </div>
          <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">   
          <div class="form-group">
            <label for="lbl_unidad_pagofac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_unidad"><?= academico::t("Academico", "Academic unit") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">                                      
                <?= Html::dropDownList("cmb_unidad_facpago", 0, array_merge([Yii::t("formulario", "All")], $arr_unidad), ["class" => "form-control", "id" => "cmb_unidad_facpago"]) ?>                                       
            </div>

            <label for="lbl_modalidad_pagofac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Modality") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">                                
                <?= Html::dropDownList("cmb_modalidad_pagofac", 0, array_merge([Yii::t("formulario", "All")], $arr_modalidad), ["class" => "form-control", "id" => "cmb_modalidad_pagofac"]) ?>
            </div>            
         </div>
    </div>        
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">   
        <div class="form-group">
            <label for="lbl_estado_pagofac" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_unidad"><?= Yii::t("formulario", "Review Status") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">                                      
                <?= Html::dropDownList("cmb_estado_pagofac", 0,  $arr_estado, ["class" => "form-control", "id" => "cmb_estado_pagofac"]) ?>                                       
            </div> 
        </div>
    </div>     
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarpagofactura" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div></br>

