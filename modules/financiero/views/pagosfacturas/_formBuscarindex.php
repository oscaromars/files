<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\financiero\Module as financiero;
use app\modules\academico\Module as academico;
use kartik\select2\Select2;
financiero::registerTranslations();
academico::registerTranslations();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
             <div class="col-sm-8 col-xs-8 col-md-8 col-lg-8">
                <!-- <input type="text" class="form-control" value="" id="txt_buscarDataEstudiante" placeholder="<= academico::t("Academico", "Buscar por Nombres, cedula") ?>">-->
                <label for="txt_buscarest" class="col-sm-3 col-md-3 col-xs-3 col-lg-3 control-label"><?= Yii::t("formulario", "Search") ?> </label>
                 <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8">
                <?php
                 echo Select2::widget([
                'name' => 'cmb_buscarestcartera',
                'id' => 'cmb_buscarestcartera',
                'value' => '0', // initial value
                'data' => $arr_alumno,
                'options' => ['placeholder' => 'Seleccionar'],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 50
                ],
            ]); ?>
            </div>
        </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2">
            <a id="btn_buscarEstudiantecartera" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div></br>
