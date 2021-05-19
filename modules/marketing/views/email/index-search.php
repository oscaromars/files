<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use yii\helpers\Html;
use app\modules\marketing\Module as marketing;
?>
<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="txt_buscar_lista" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= marketing::t("marketing", "List") ?></label>
            <div class="col-sm-6 col-md-6 col-xs-6 col-lg-6">
               <input type="text" class="form-control " value="" id="txt_buscar_lista" data-type="alfa" placeholder="<?= marketing::t("marketing", "List") ?>">                 
            </div>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3 "></div>
        </div>
    </div>
   
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscarDataLista" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div>

