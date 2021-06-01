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
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_evaluar"><?= academico::t("Academico", "List parallels") ?></span></h3>
</div>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
    <div class="col-md-7 col-sm-7 col-xs-7 col-lg-7">
        <div class="form-group">
            <h4><span id="lbl_general"><?= Yii::t("formulario", "Data Para") ?></span></h4> 
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_codigo" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_codigo"><?= Yii::t("formulario", "Code") ?></label>
                <span for="txt_codigo" class="col-sm-8 col-md-8 col-xs-8 col-lg-8 control-label" id="lbl_codigo"><?= $data_promo['ppro_codigo'] ?> </span> 
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_anio" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_anio"><?= academico::t("Academico", "Year") ?></label>
                <span for="txt_anio" class="col-sm-8 col-md-8 col-xs-8 col-lg-8 control-label" id="lbl_anio"><?= $data_promo['ppro_anio'] ?> </span> 
            </div>
        </div>         
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_mes" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label" id="lbl_mes"><?= academico::t("Academico", "Month") ?></label>
                <span for="txt_mes" class="col-sm-8 col-md-8 col-xs-8 col-lg-8 control-label" id="lbl_mes"><?= $data_promo['nombre_mes'] ?> </span> 
            </div>
        </div> 
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_unidad" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("Academico", "Academic unit") ?></label> 
                <span for="txt_unidad" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label"><?= $data_promo ['uaca_nombre'] ?> </span> 
            </div>
        </div>       
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
         <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_modalidad" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= academico::t("Academico", "Modality") ?></label> 
                <span for="txt_modalidad" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label"><?= $data_promo['mod_nombre'] ?> </span> 
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_programa" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= Yii::t("formulario", "Program") ?></label> 
                <span for="txt_programa" class="col-sm-8 col-md-8 col-xs-8 col-lg-8  control-label"><?= $data_promo['eaca_nombre'] ?> </span> 
            </div>
        </div>        
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div>
            <?=
            $this->render('_listarParalelosGrid', [
                'model' => $model,
            ]);
            ?>
        </div>
    </div> 
