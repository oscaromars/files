<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\modules\academico\Module as academico;
academico::registerTranslations();
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">       
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_buscarData" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Search")  ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <input type="text" class="form-control" value="" id="txt_buscarData" placeholder="<?= Yii::t("accion","Search").": ".Yii::t("formulario", "Names").", ".Yii::t("formulario", 'Last Names').", ".Yii::t("formulario", "Dni").", ".Yii::t("perfil", 'Email') ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_inicio" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "Start date") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_ini',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control", "id" => "txt_fecha_ini", "placeholder" => Yii::t("formulario", "Start date")],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => Yii::$app->params["dateByDatePicker"],
                        ]]
                    );
                    ?>
                </div>
            </div> 
        </div>  
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_fin" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label"><?= Yii::t("formulario", "End date") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_fin',
                        'value' => '',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control", "id" => "txt_fecha_fin", "placeholder" => Yii::t("formulario", "End date")],
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_unidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_unidad"><?= Yii::t("formulario", "Academic unit") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">                                      
                    <?= Html::dropDownList("cmb_unidad", 0, array_merge([Yii::t("formulario", "Select")], $arr_unidad), ["class" => "form-control", "id" => "cmb_unidad"]) ?>                                       
                </div>
            </div>
        </div>  
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_carrera_programa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_unidad"><?= academico::t("Academico", "Career/Program") ?></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">                                      
                    <?= Html::dropDownList("cmb_carrera_programa", 0, array_merge([Yii::t("formulario", "Select")], $arr_carrera_prog), ["class" => "form-control", "id" => "cmb_carrera_programa"]) ?>                                       
                </div>
            </div>
        </div> 
    </div>       
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="col-sm-8 col-md-8 col-xs-8 col-lg-8"></div>
        <div class="col-sm-2 col-md-2 col-xs-4 col-lg-2">                
            <a id="btn_buscar" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div>