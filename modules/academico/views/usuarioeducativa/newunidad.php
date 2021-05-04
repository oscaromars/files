<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as academico;

financiero::registerTranslations();
academico::registerTranslations();
?>
<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Create Unit") ?></span><br/>    
</div>
<br><br><br><br>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_periodonewunidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("matriculacion", 'Academic Period') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodonewunidad", 0, $arr_periodoAcademico, ["class" => "form-control pro_combo", "id" => "cmb_periodonewunidad"]) ?>
            </div> 
            <label for="lbl_cursounidad" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Course") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                 <?= Html::dropDownList("cmb_cursounidad", 0, $arr_curso, ["class" => "form-control pro_combo", "id" => "cmb_cursounidad"]) ?>
            </div>   
        </div>
    </div>   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">  
            <label for="txt_codigonewunidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label" id="lbl_codigonewunidad"><?= academico::t("matriculacion", 'Código Unidad') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_codigonewunidad" data-type="number" data-keydown="true" placeholder="<?= academico::t("matriculacion", 'Código Unidad') ?>">                    
            </div>                         
            <label for="txt_descripcionnewunidad" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_descripcionnewunidad"><?= Yii::t("formulario", "Nombre Unidad") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_descripcionnewunidad" data-type="all" data-keydown="true" placeholder="<?= academico::t("matriculacion", 'Nombre Unidad') ?>"> 
         </div>
    </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_newunidad" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?></a>
        </div>
    
</div>
</br>
</form>