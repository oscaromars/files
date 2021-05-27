<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\modules\financiero\Module as financiero;
financiero::registerTranslations();
?> 
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Beneficiary data") ?></span></h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_primer_nombre" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Yii::t("formulario", "Name") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_primer_nombre" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "First Name") ?>">                
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_primer_apellido" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Yii::t("formulario", "Last Name1") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_primer_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_tipo_dni" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label keyupmce"><?= Yii::t("formulario", "DNI 1") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <?= Html::dropDownList("s", 0, $tipos_dni, ["class" => "form-control", "id" => "cmb_tipo_dni"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" id="Divcedula">
            <div class="form-group">
                <label for="txt_cedula" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Yii::t("formulario", "Number") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <input type="text" maxlength="10" class="form-control PBvalidation keyupmce" id="txt_cedula" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "National identity document") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" style="display: none;" id="Divpasaporte">
            <div class="form-group">
                <label for="txt_pasaporte" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Yii::t("formulario", "Number") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <input type="text" maxlength="15" class="form-control keyupmce" id="txt_pasaporte" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Passport") ?>">
                </div>
            </div>
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_correo" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Yii::t("formulario", "Email") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <input type="text" class="form-control PBvalidation" id="txt_correo" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_celular" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Yii::t("formulario", "CellPhone") ?> <span class="text-danger">*</span></label>		
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <input type="text" class="form-control PBvalidation" id="txt_celular" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">            
                <label for="cmb_pais_dom" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label"><?= Yii::t("formulario", "Country") ?> <span class="text-danger">*</span></label>
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <?= Html::dropDownList("cmb_pais_dom", 1, $arr_pais_dom, ["class" => "form-control", "id" => "cmb_pais_dom"]) ?>
                </div>
            </div>
        </div>        
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
        <div class="col-md-10"></div>
        <div class="col-md-2">
            <a id="paso1next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
        </div>
    </div>   
</form>