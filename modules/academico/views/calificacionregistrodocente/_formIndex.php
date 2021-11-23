<?php

use app\modules\academico\Module as academico;
use kartik\select2\Select2;
use yii\helpers\Html;
Academico::registerTranslations();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$leyendarc = '<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                <div class="form-group">
                    <div class="col-sm-10 col-md-10 col-xs-10 col-lg-10">
                        <div style = "width: 650px;" class="alert alert-info alert-dismissible"><span style="font-weight: bold"> Nota: </span> Los estados designa al estudiante si ha pasado la materia, ya sea, en asistencia y academico</div>
                    </div>
                </div>
            </div>';

?>
<style>
    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
    height: 98% !important;
}
</style>
 <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
       <br>



         <!--<div class="col-sm-12 col-md-12 col-xs-12 col-lg-12 "></div>
        <div class="col-sm-12 col-md-12 col-xs-12 col-lg-12">
            <a id="btn_download_acta" href="javascript:" class="btn btn-primary btn-block"> <?=academico::t("Academico", "Acta de Calificaciones")?></a>
        </div>
    </div>  -->



<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <label for="txt_buscarest" class="col-lg-2 col-md-2 col-sm-12 col-xs-12 control-label"><?=Yii::t("formulario", "Student")?>  </label>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" >
                    <?php
echo Select2::widget([
	'name' => 'cmb_buscarest',
	'id' => 'cmb_buscarest',
	'value' => $arr_initial, // initial value
	'data' => $arr_alumno,
	'options' => ['placeholder' => 'Seleccionar'],
	'pluginOptions' => [
		'tags' => true,
		'tokenSeparators' => [',', ' '],
		'maximumInputLength' => 50,
	],
]); ?>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <a id="btn_limpiarbuscador" href="javascript:" class="btn btn-default btn-block"> <?=Yii::t("formulario", "Limpiar busqueda")?></a>
            </div>
        </div>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12"> <br>
        <div class="form-group">
            <label for="cmb_periodo_clfc" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("Academico", "Period")?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?=Html::dropDownList("cmb_periodo_clfc", 1, $arr_periodos, ["class" => "form-control", "id" => "cmb_periodo_clfc"])?>
            </div>
              <label for="cmb_unidad_bus" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("Academico", "Academic unit")?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?=Html::dropDownList("cmb_unidad_bus", 1, $arr_ninteres, ["class" => "form-control", "id" => "cmb_unidad_bus"])?>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="cmb_modalidad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("Academico", "Modality")?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?=Html::dropDownList("cmb_modalidad", 1, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"])?>
            </div>

             <label for="cmb_profesor_clfc" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("Academico", "Teacher")?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?=Html::dropDownList("cmb_profesor_clfc", 0, $arr_profesor_all, ["class" => "form-control", "id" => "cmb_profesor_clfc"])?>
            </div>


        </div>
    </div>
     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">

           <label for="cmb_materiabus" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?=academico::t("Academico", "Subject")?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                    <?=Html::dropDownList("cmb_materiabus", 5, $arr_asignatura, ["class" => "form-control", "id" => "cmb_materiabus"])?>
            </div>







        </div>


    </div>
    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">
            <a id="btn_buscarDataestClfcns" href="javascript:" class="btn btn-primary btn-block"> <?=academico::t("Academico", "Search")?></a>
        </div>
          <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">
          <br>
        </div>
         <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 "></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">
            <a id="btn_download_acta" href="javascript:" class="btn btn-link btn-block"> <?=academico::t("Academico", "Acta de Calificaciones")?></a>
        </div>
    </div>
</div>

<?php echo $leyendarc; ?>

