<?php

use app\modules\academico\Module as academico;
use yii\helpers\Html;
academico::registerTranslations();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div>
    <h3><?=Academico::t("matriculacion", "Registro en Línea - Administrativo")?></h3>
    <h4><?=Academico::t("matriculacion", "Listado de estudiante a matricular")?></h4>
</div>

<div class="row">
    <br><br>
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_buscarDataCreate" class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label"><?=Yii::t("formulario", "Student")?></label>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <input type="text" class="form-control" value="" id="txt_buscarDataCreate" placeholder="<?=Yii::t("solicitud_ins", "Search by SSN/Passport or Names")?>">
            </div>
        </div>
    </div>
    <div class='col-xs-12 col-sm-12 col-md-6 col-lg-6'>
        <div class="form-group">
            <label for="cmb_planificacion" class="col-xs-12 col-sm-12 col-md-5 col-lg-5 control-label"><?=academico::t("matriculacion", "Academic Period")?></label>
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <?=Html::dropDownList("cmb_planificacion", 0, $arr_pla, ["class" => "form-control", "id" => "cmb_planificacion"])?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2">
            <a id="btn_buscarEstudiantes" href="javascript:" class="btn btn-primary btn-block"> <?=Yii::t("formulario", "Search")?></a>
        </div>
    </div>




</div>

