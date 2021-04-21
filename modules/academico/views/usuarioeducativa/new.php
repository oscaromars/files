<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as academico;

financiero::registerTranslations();
academico::registerTranslations();
?>
<div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Upload course") ?></span><br/>    
</div>
<br><br><br><br>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_periodonew" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("matriculacion", 'Academic Period') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodonew", 0, $arr_periodoAcademico, ["class" => "form-control pro_combo", "id" => "cmb_periodonew"]) ?>
            </div>
            <label for="lbl_asignatura" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Subject") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                 <?= Html::dropDownList("cmb_asignaturanew", 0, $arr_asignatura, ["class" => "form-control pro_combo", "id" => "cmb_asignaturanew"]) ?>
            </div>
        </div>
    </div>   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_codigonew" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label" id="lbl_codigonew"><?= academico::t("matriculacion", 'Código Aula') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_codigonew" data-type="number" data-keydown="true" placeholder="<?= academico::t("matriculacion", 'Código Aula') ?>">                    
            </div>               
            <label for="txt_aulanew" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_aulanew"><?= Yii::t("formulario", "Nombre Aula") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_aulanew" data-type="all" data-keydown="true" placeholder="<?= academico::t("matriculacion", 'Nombre Aula') ?>"> 
         </div>
    </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_newcurso" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Save") ?></a>
        </div>
    
</div>
</br>
</form>