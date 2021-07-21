<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Financiamiento") ?></span></h3><br><br></br>
</div><br><br></br>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">                      
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align: right;">
            <input type="checkbox" id="chk_creditodirecto" data-type="alfa" data-keydown="true" placeholder="" >
        </div>
        <label for="chk_creditodirecto" class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><?= Yii::t("formulario", "Crédito Directo") ?> </label>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">                     
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align: right;">
            <input type="checkbox" id="chk_creditobancario" data-type="alfa" data-keydown="true" placeholder="" >   
        </div>
        <label for="chk_creditobancario" class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><?= Yii::t("formulario", "Crédito Bancario") ?> </label>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">                     
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align: right;">
            <input type="checkbox" id="chk_pagocontado" data-type="alfa" data-keydown="true" placeholder="" >
        </div>
        <label for="chk_pagocontado" class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><?= Yii::t("formulario", "Pago al Contado") ?> </label>
    </div><br><br></br>
</div>

<br></br>
<br></br>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <div class="col-md-2">
        <a id="paso3back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"> &nbsp;</div>
    <div class="col-md-2">
        <a id="paso3next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
    </div>
</div>