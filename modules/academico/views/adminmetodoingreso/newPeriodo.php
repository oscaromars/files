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
    <h3><span id="lbl_titulo"><?= academico::t("Academico", "Create period income method") ?></span><br/>    
</div>

<form class="form-horizontal" enctype="multipart/form-data" >
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_anio" class="col-sm-4 control-label" id="lbl_periodo"><?= academico::t("Academico", "Year") ?></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control PBvalidation" value="" id="txt_anio" data-type="graduacion" data-keydown="true" placeholder="<?= Yii::t("academico", "Year") ?>">
                </div>
            </div>
        </div>  
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_mes" class="col-sm-4 control-label" id="lbl_periodo"><?= academico::t("Academico", "Month") ?></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_mes", 1, $mes, ["class" => "form-control", "id" => "cmb_mes"]) ?>
                </div>
            </div>
        </div>  
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_unidad" class="col-sm-4 control-label" id="lbl_periodo"><?= academico::t("Academico", "Academic unit") ?></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_unidad", 1, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad"]) ?>
                </div>
            </div>
        </div>  
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_modalidad" class="col-sm-4 control-label" id="lbl_periodo"><?= academico::t("Academico", "Modality") ?></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_modalidad", 1, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
                </div>
            </div>
        </div>  
    </div> 
    
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_fecha_desde" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label"><?= Yii::t("formulario", "Start date") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_desde',
                        'value' => '',
                        'disabled' => $habilita,
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_desde", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Start date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_fecha_hasta" class="col-sm-4 col-md-4 col-xs-4 col-lg-4  control-label"><?= Yii::t("formulario", "End date") ?></label>
                <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_hasta',
                        'value' => '',
                        'disabled' => $habilita,
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_hasta", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "End date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-12 col-sm-12 col-xs-12 col-lg-12'>        
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="txt_metodo_ingreso" class="col-sm-4 control-label" id="lbl_periodo"><?= admision::t("Solicitudes", "Income Method") ?></label>
                <div class="col-sm-8">
                    <?= Html::dropDownList("cmb_metodo_ingreso", 1, $arr_metodos, ["class" => "form-control", "id" => "cmb_metodo_ingreso"]) ?>
                </div>
            </div>
        </div>
    </div>        
</form>