<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as aspirante;

admision::registerTranslations();
academico::registerTranslations();
financiero::registerTranslations();
aspirante::registerTranslations();
?>

<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Create Promotion Program") ?></span><br/>    
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
<form class="form-horizontal" enctype="multipart/form-data" >
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_anio" class="col-sm-4 control-label" id="lbl_anio"><?= academico::t("Academico", "Year") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" value="" id="txt_anio" data-type="graduacion" data-keydown="true" placeholder="<?= Yii::t("academico", "Year") ?>">
                </div>
            </div>
        </div>  
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_mes" class="col-sm-4 control-label" id="lbl_mes"><?= academico::t("Academico", "Month") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_mes", 0, $mes, ["class" => "form-control", "id" => "cmb_mes"]) ?>
                </div>
            </div>
        </div>  
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_unidad" class="col-sm-4 control-label" id="lbl_unidad"><?= academico::t("Academico", "Academic unit") ?></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_unidad", 2, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>  
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_modalidad" class="col-sm-4 control-label" id="lbl_modalidad"><?= academico::t("Academico", "Modality") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_modalidad", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                </div>
            </div>
        </div>  
    </div>    
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_programa" class="col-sm-4 control-label" id="lbl_programa"><?= Yii::t("formulario", "Program") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_programa", 0, $arr_programa1, ["class" => "form-control", "id" => "cmb_programa"]) ?>
                </div>
            </div>
        </div> 
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_paralelo" class="col-sm-4 control-label" id="lbl_paralelo"><?= academico::t("Academico", "Parallel") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" value="" id="txt_paralelo" data-type="number" data-keydown="true" placeholder="<?= Yii::t("academico", "Parallel") ?>">
                </div>
            </div>
        </div>
    </div> 
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>      
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_cupo" class="col-sm-4 control-label" id="lbl_cupo"><?= academico::t("Academico", "Quota") ?><span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" value="" id="txt_cupo" data-type="number" data-keydown="true" placeholder="<?= Yii::t("academico", "Quota") ?>">
                </div>
            </div>
        </div>
    </div>   
    <!--<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_grabar" href="javascript:" class="btn btn-primary btn-block"> <? Yii::t("formulario", "Save") ?></a>
        </div>
    </div>-->
</form>