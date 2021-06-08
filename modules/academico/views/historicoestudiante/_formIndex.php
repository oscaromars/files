<?php

use yii\web\JsExpression;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;



/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="txt_buscarData" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control" value="" id="txt_buscarData" placeholder="<?= Yii::t("solicitud_ins", "Search by Dni or Names") ?>">
            </div>
        </div>
    </div>
    
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_unidadbus" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_unidadbus", 0,  $arr_ninteres , ["class" => "form-control", "id" => "cmb_unidadbus"]) ?>
            </div>
            <label for="cmb_modalidadbus" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidadbus", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidadbus"]) ?>
            </div>      
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">      
            <label for="cmb_carrerabus" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("academico", "Career") . ' /Programa' ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_carrerabus", 0, $arr_carrerra1, ["class" => "form-control", "id" => "cmb_carrerabus"]) ?>
            </div> 
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarDatahistoest" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div>
    
    
    

