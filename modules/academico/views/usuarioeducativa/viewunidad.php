<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as academico;

financiero::registerTranslations();
academico::registerTranslations();
//print_r($arr_unidad);
?>
<?= Html::hiddenInput('txth_unidadid', $_GET['ceuni_id'], ['id' => 'txth_unidadid']); ?>
<!-- <div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "View Unit") ?></span><br/>    
</div>
<br><br><br><br>-->
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_periodoviewunidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("matriculacion", 'Academic Period') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodoviewunidad", $arr_unidad ["paca_id"], $arr_periodoAcademico, ["class" => "form-control pro_combo", "id" => "cmb_periodoviewunidad", "disabled" => "true"]) ?>
            </div> 
            <label for="lbl_cursounidad" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Course") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                 <?= Html::dropDownList("cmb_cursounidad", $arr_unidad ["cedu_id"], $arr_curso, ["class" => "form-control pro_combo", "id" => "cmb_cursounidad", "disabled" => "true"]) ?>
            </div>   
        </div>
    </div>   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">  
            <label for="txt_codigoviewunidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label" id="lbl_codigoviewunidad"><?= academico::t("matriculacion", 'Código Unidad') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?php echo $arr_unidad ["ceuni_codigo_unidad"]?>" id="txt_codigoviewunidad" data-type="number" disabled = "true" data-keydown="true" placeholder="<?= academico::t("matriculacion", 'Código Unidad') ?>">                    
            </div>                         
            <label for="txt_descripcionviewunidad" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_descripcionviewunidad"><?= Yii::t("formulario", "Nombre Unidad") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?php echo $arr_unidad ["ceuni_descripcion_unidad"]?>" id="txt_descripcionviewunidad" data-type="all" disabled = "true" data-keydown="true" placeholder="<?= academico::t("matriculacion", 'Nombre Unidad') ?>"> 
         </div>
    </div> 
</div>
</br>
</form>