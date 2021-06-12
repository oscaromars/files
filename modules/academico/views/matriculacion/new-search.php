<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\widgets\PbSearchBox\PbSearchBox;
use app\models\Utilities;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\modules\academico\Module as academico;
academico::registerTranslations();

?>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="form-group">
            <label for="txt_buscarData" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8 ">
                <input type="text" class="form-control" value="" id="txt_buscarData" placeholder="<?= Yii::t("solicitud_ins", "Search by Names") ?> <?= academico::t("Academico", "Subject") ?>">
            </div>
        </div>
    </div>  

   

    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="form-group">                 
            <label for="cmb_carrera" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("matriculacion", "Program") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_carrera", 0, $arr_carrera, ["class" => "form-control", "id" => "cmb_carrera"]) ?>
            </div>
            <label for="cmb_per_acad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("matriculacion", "Academic Period") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_per_acad", 0, $arr_pla_per, ["class" => "form-control", "id" => "cmb_per_acad"]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
        <div class="form-group">                 
            <label for="cmb_mod" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("matriculacion", "Modality") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_mod", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_mod"]) ?>
            </div>
            <label for="cmb_status" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("matriculacion", "Status") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_status", -2, $arr_status, ["class" => "form-control", "id" => "cmb_status"]) ?>
            </div>
        </div>
    </div>

 
    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
        <div class="form-group">
            <label for="lbl_inicio" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Start date") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_inilist',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_inilist", "placeholder" => Yii::t("formulario", "Start date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
            <label for="lbl_fin" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "End date") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_finlist',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_finlist", "placeholder" => Yii::t("formulario", "End date")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
        </div>
    </div>

 


    

    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                         
            <a id="btn_buscarData" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>


    </div>