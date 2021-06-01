<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as academico;

financiero::registerTranslations();
academico::registerTranslations();
//print_r($arr_curso);
?>
<?= Html::hiddenInput('txth_cursoid', $_GET['cedu_id'], ['id' => 'txth_cursoid']); ?>
<!-- <div class="col-md-12">    
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "View course") ?></span><br/>    
</div>-->
<br><br><br><br>
<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
    <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
</div>
<form class="form-horizontal" enctype="multipart/form-data" >  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_periodoview" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("matriculacion", 'Academic Period') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodoview", $arr_curso["paca_id"], $arr_periodoAcademico, ["class" => "form-control pro_combo", "id" => "cmb_periodoview", "disabled" => "true"]) ?>
            </div>
            <!-- <label for="lbl_asignatura" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Subject") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                 < Html::dropDownList("cmb_asignaturaview", $arr_curso["asi_id"], $arr_asignatura, ["class" => "form-control pro_combo", "id" => "cmb_asignaturaview", "disabled" => "true"]) ?>
            </div> -->
            <label for="txt_codigoview" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label" id="lbl_codigoview"><?= academico::t("matriculacion", 'Código Aula') ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?php echo $arr_curso['cedu_asi_id']; ?>" id="txt_codigoview" data-type="number" disabled= "true" data-keydown="true" placeholder="<?= academico::t("matriculacion", 'Código Aula') ?>">                    
            </div>  
        </div>
    </div>   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">                         
            <label for="txt_aulaview" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label" id="lbl_aulaview"><?= Yii::t("formulario", "Nombre Aula") ?><span class="text-danger"> *</span></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
            <input type="text" class="form-control PBvalidation keyupmce" value="<?php echo $arr_curso['cedu_asi_nombre']; ?>" id="txt_aulaview" data-type="all" disabled= "true" data-keydown="true" placeholder="<?= academico::t("matriculacion", 'Nombre Aula') ?>"> 
         </div>
    </div>
        <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_editcurso" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Editar") ?></a>
        </div>-->
    
</div>
</br>
</form>