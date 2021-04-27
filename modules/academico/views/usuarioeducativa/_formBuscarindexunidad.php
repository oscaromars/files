<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as academico;

financiero::registerTranslations();
academico::registerTranslations();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_buscarDataunidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-xs-8 col-md-8 col-lg-8">
                <input type="text" class="form-control" value="" id="txt_buscarDataunidad" placeholder="<?= academico::t("Academico", "Search by Unit") ?>">
            </div>
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_periodounidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("matriculacion", 'Academic Period') ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodounidad", 0, $arr_periodoAcademico, ["class" => "form-control pro_combo", "id" => "cmb_periodounidad"]) ?>
            </div>
            <label for="lbl_curso" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= academico::t("Academico", "Course") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                 <?= Html::dropDownList("cmb_curso", 0, $arr_curso, ["class" => "form-control pro_combo", "id" => "cmb_curso"]) ?>
            </div> 
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_buscarUnidad" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div></br>
