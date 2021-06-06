<?php

use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?= Html::hiddenInput('txth_pids', base64_decode($_GET['per_id']), ['id' => 'txth_pids']); ?>

<?= Html::hiddenInput('txth_perids', $_GET['per_id'], ['id' => 'txth_perids']); ?>

<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="txt_buscarData" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Estudiante:") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control" value="<?php echo $arr_persona['per_pri_nombre'] . " " . $arr_persona['per_seg_nombre']  . " " . $arr_persona['per_pri_apellido'] . " " . $arr_persona['per_seg_apellido']?>" id="txt_buscarData" disabled=true>
            </div>
        </div>
    </div>
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_unidadbus" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_unidadbus", $arr_persona["uaca_id"],  $arr_ninteres , ["class" => "form-control", "disabled"=> "true", "id" => "cmb_unidadbus"]) ?>
            </div>
            <label for="cmb_modalidadbus" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidadbus", $arr_persona["mod_id"], $arr_modalidad, ["class" => "form-control","disabled"=> "true", "id" => "cmb_modalidadbus"]) ?>
            </div>      
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">      
            <label for="cmb_carrerabus" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("academico", "Career") . ' /Programa' ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_carrerabus", $arr_persona["eaca_id"], $arr_carrerra1, ["class" => "form-control","disabled"=> "true",  "id" => "cmb_carrerabus"])  ?>
            </div> 
            <label for="cmb_matriculabus" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Matricula") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <input type="text" class="form-control" value="<?php echo $arr_persona['est_matricula'] ?>" id="cmb_matriculabus" disabled=true>
            </div>    
        </div>
    </div>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            
        </div>
    </div>
</div>
    
    
    

