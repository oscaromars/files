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
<?= Html::hiddenInput('txth_ids', $distributivo_model->daca_id, ['id' => 'txth_ids']); ?>
<?= Html::hiddenInput('txth_daes_id', $estudiante_model->daes_id, ['id' => 'txth_daes_id']); ?>
<center> <label for="txt_estudinate" "><h2> <?= $estudiante_model->est->persona->per_pri_nombre . ' ' . $estudiante_model->est->persona->per_seg_nombre . ' ' . $estudiante_model->est->persona->per_pri_apellido . ' ' . $estudiante_model->est->persona->per_seg_apellido ?></h2></label></center>
<br/> 
<div class="row">
    
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       
        <div class="form-group">            
            <label for="txt_unidad_dis" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::input("text", "txt_unidad_dis", $distributivo_model->uaca->uaca_nombre, ["class" => "form-control", "id" => "txt_unidad_dis", "disabled" => "disabled"]) ?>
            </div>
            <label for="txt_modalidad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::input("text", "txt_modalidad",  $distributivo_model->mod->mod_nombre, ["class" => "form-control", "id" => "txt_modalidad", "disabled" => "disabled"]) ?>
            </div>      
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="txt_periodo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::input("text", "txt_periodo",  $distributivo_model->paca->sem->saca_nombre, ["class" => "form-control", "id" => "txt_periodo", "disabled" => "disabled"]) ?>
            </div>       
            <label for="txt_materia" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Subject") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::input("text", "txt_materia",  $distributivo_model->asi->asi_nombre, ["class" => "form-control", "id" => "txt_materia", "disabled" => "disabled"]) ?>
            </div> 
        </div>                                            
    </div>    
   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">                        
            <label for="txt_profesor" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Teacher") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?= Html::input("text", "txt_profesor", $distributivo_model->pro->per->per_pri_nombre.' '.$distributivo_model->pro->per->per_seg_nombre.' '.$distributivo_model->pro->per->per_pri_apellido.' '.$distributivo_model->pro->per->per_seg_apellido, ["class" => "form-control", "id" => "txt_profesor", "disabled" => "disabled"]) ?>
            </div>
             <label for="txt_paralelo" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Paralelo") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
       
                  <?= Html::dropDownList("cmb_paralelo", $distributivo_model->daca_id, $paralelos, ["class" => "form-control", "id" => "cmb_paralelo"]) ?>
            </div>
             
        </div>
    </div> 
    
    
</div>