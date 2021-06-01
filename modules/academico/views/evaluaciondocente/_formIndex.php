<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_buscarDataProfesor" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-xs-8 col-md-8 col-lg-8">
                <input type="text" class="form-control" value="" id="txt_buscarDataProfesor" placeholder="<?= Yii::t("formulario", "Search by Names") . ' ' . academico::t("Academico", "Teacher") ?>">
            </div>
        </div>
    </div>      
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_tipoevaluacion" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Evaluation Type"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_tipoevaluacion", 0, $arr_tipoevaluacion, ["class" => "form-control", "id" => "cmb_tipoevaluacion"]) ?>
            </div>   
            <label for="lbl_semestre" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Semester"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_semestre", 0, $arr_semetre, ["class" => "form-control", "id" => "cmb_semestre"]) ?>
            </div>                  
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_buscarEvaluacion" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div></br>
