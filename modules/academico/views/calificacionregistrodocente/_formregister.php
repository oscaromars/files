<?php

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;

academico::registerTranslations();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
    <div class="form-group">                 
        <label for="cmb_periodo" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= academico::t("Academico", "Period") ?></label>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">  
                <?= Html::dropDownList("cmb_periodo",0,$arr_periodos, ["class" => "form-control", "id" => "cmb_periodo"]) ?>              
        </div>
    </div>
</div>
<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
    <div class="form-group">                 
        <label for="cmb_unidad" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <?= Html::dropDownList("cmb_unidad", 0, $arr_ninteres,["class" => "form-control", "id" => "cmb_unidad"]) ?>
        </div>
    </div>
</div>
<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
    <div class="form-group"> 
        <label for="cmb_modalidad" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= academico::t("Academico", "Modality") ?></label>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <?= Html::dropDownList("cmb_modalidad", 0,$arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
        </div>
    </div>
</div>
<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
    <div class="form-group"> 
        <label for="cmb_profesor_rc" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= academico::t("Academico", "Teacher") ?></label>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <?= Html::dropDownList("cmb_profesor_clfc", 0,$arr_profesor_all, ["class" => "form-control", "id" => "cmb_profesor_rc"]) ?>
        </div>
    </div>
</div>
<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
    <div class="form-group"> 
        <label for="cmb_materia" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label">
            <?= academico::t("Academico", "Course") ?></label>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <?= Html::dropDownList("cmb_materia", 0,$arr_asignatura,["class" => "form-control", "id" => "cmb_materia"]) ?>
        </div>
    </div>
</div>
<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
    <div class="form-group"> 
        <label for="cmb_parcial" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?= academico::t("Academico", "Partial") ?></label>
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                <?= Html::dropDownList("cmb_parcial", 0,$arr_parcial, ["class" => "form-control", "id" => "cmb_parcial"]) ?>
        </div>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 "></div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">                
        <a id="btn_buscarDataregistro" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
    </div>
</div>
</div>


