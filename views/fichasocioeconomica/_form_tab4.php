<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Idiomas") ?></span></h3>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_idioma1" class="col-sm-5 control-label"><?= Yii::t("formulario", "Idioma 1") ?> <span class="text-danger">*</span> </label>
        
    </div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_escrito1" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Escrito") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_escrito1" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel Escrito") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_leido1" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Leído") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_leido1" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel leído") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_hablado1" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Hablado") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_hablado1" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel Hablado") ?>">
        </div>
    </div><br><br></br> 
</div><br><br></br> 

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_idioma2" class="col-sm-5 control-label"><?= Yii::t("formulario", "Idioma 2") ?> <span class="text-danger">*</span> </label>
        
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_escrito2" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Escrito") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_escrito2" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel Escrito") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_leido2" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Leído") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_leido2" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel leído") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_hablado2" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Hablado") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_hablado2" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel Hablado") ?>">
        </div>
    </div><br><br></br> 
</div><br><br></br> 

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_idioma3" class="col-sm-5 control-label"><?= Yii::t("formulario", "Idioma 3") ?></label>
        
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_escrito3" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Escrito") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_escrito3" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel Escrito") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_leido3" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Leído") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_leido3" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel leído") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_hablado3" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Hablado") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_hablado3" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel Hablado") ?>">
        </div>
    </div><br><br></br>
</div><br><br></br> 

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_idioma4" class="col-sm-5 control-label"><?= Yii::t("formulario", "Idioma 4") ?></label>
        
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_escrito4" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Escrito") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_escrito4" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel Escrito") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_leido4" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Leído") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_leido4" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel leído") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_nivel_hablado4" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nivel Hablado") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_nivel_hablado4" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel Hablado") ?>">
        </div>
    </div><br><br></br>
</div><br><br></br> 

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <div class="col-md-2">
        <a id="paso4back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
    </div>
    <div class="col-md-8"></div>
    <div class="col-md-2">
        <a id="paso4next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
    </div>
</div>