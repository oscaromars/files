<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use app\modules\academico\Module as academico;
?>
<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 ">
    <h3><span id="lbl_Personeria"><?= academico::t("Academico", "Teacher list unchecked") ?></span></h3>
    <br>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_buscarDataProfesor" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Search") ?></label>
            <div class="col-sm-8 col-xs-8 col-md-8 col-lg-8">
                <input type="text" class="form-control" value="" id="txt_buscarDataProfesor" placeholder="<?= Yii::t("formulario", "Search by Names") .' '. academico::t("Academico", "Teacher")?>">
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="txt_buscarDataMateria" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Matter") ?></label>
            <div class="col-sm-8 col-xs-8 col-md-8 col-lg-8">
                <input type="text" class="form-control" value="" id="txt_buscarDataMateria" placeholder="<?= academico::t("Academico", "Search by Matter") ?>">
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="lbl_inicio" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "Start date") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
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
            <label for="lbl_fin" class="col-sm-2 col-md-2 col-xs-2 col-lg-2 control-label"><?= Yii::t("formulario", "End date") ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">            
            <label for="cmb_unidad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_unidad", 0,  $arr_unidad , ["class" => "form-control", "id" => "cmb_unidad"]) ?>
            </div>
            <label for="cmb_modalidad" class="col-sm-2 col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode") ?></label>
            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidad", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidad"]) ?>
            </div>      
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="lbl_periodo" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodo", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo"]) ?>
            </div>                        
            <label for="lbl_tipo" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= academico::t("Academico", "Dial type"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_tipo", 0, $arr_tipo, ["class" => "form-control", "id" => "cmb_tipo"]) ?>
            </div>        
        </div>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_buscarNoMarcacion" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
        </div>
    </div>
</div></br>
