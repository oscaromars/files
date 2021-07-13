<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Academic") ?></span></h3>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_colegio" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nombre del Colegio") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_colegio" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nombre de la Institutión") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_tipo_colegio" class="col-sm-5 control-label"><?= Yii::t("formulario", "Tipo de Colegio") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <?= Html::dropDownList("txt_tipo_colegio", "", $tipos_institucion, ["class" => "form-control", "id" => "txt_tipo_colegio"]) ?>
        </div>
    </div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_pais_col" class="col-sm-5 control-label"><?= Yii::t("formulario", "Country") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_pais_col", 0, $arr_pais_col, ["class" => "form-control", "id" => "cmb_pais_col"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_prov_col" class="col-sm-5 control-label"><?= Yii::t("formulario", "State") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_prov_col", 0, $arr_prov_col, ["class" => "form-control", "id" => "cmb_prov_col"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="cmb_ciu_col" class="col-sm-5 control-label"><?= Yii::t("formulario", "City") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <?= Html::dropDownList("cmb_ciu_col", 0, $arr_ciu_col, ["class" => "form-control", "id" => "cmb_ciu_col"]) ?>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_especializacion" class="col-sm-5 control-label"><?= Yii::t("formulario", "Especialización de Bachiller") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_especializacion" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Especialización de Bachiller") ?>">
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_bachillerato" class="col-sm-5 control-label"><?= Yii::t("formulario", "Bachillerato Internacional") ?> <span class="text-danger">*</span> </label>
        <div class="col-sm-7">
            <input type="text" class="form-control PBvalidation keyupmce" id="txt_bachillerato" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Bachillerato Internacional") ?>">
        </div>
    </div><br></br><br></br>
</div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <div class="form-group">
        <label for="txt_homologacion" class="col-sm-5 control-label"><?= Yii::t("bienestar", "Homologación") ?><span class="text-danger">*</span></label>
        <div class="col-sm-7">
            <label>                
                <input type="radio" name="signup-dis" id="signup-hom" value="1" checked> Si<br>                   
            </label>
            <label>            
                <input type="radio" name="signup-dis_no" id="signup-hom_no" value="2" > No<br>
            </label>
        </div> 
    </div><br>
</div>  
<div id="homologacion" style="display: block;" >
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
        <div class="form-group">
            <label for="txt_universidad" class="col-sm-5 control-label"><?= Yii::t("bienestar", "Universidad de la que proviene") ?></label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="txt_universidad" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Nombre de la Universidad") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
        <div class="form-group">
            <label for="txt_carrera" class="col-sm-5 control-label"><?= Yii::t("bienestar", "Carrera") ?></label>
            <div class="col-sm-7">
                <input type="text" class="form-control" id="txt_carrera" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Carrera") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
        <div class="form-group">
            <label for="txt_graduado" class="col-sm-5 control-label"><?= Yii::t("formulario", "¿Graduado?") ?></label>
            <div class="col-sm-7">
                <?= Html::dropDownList("txt_graduado", 0, $graduacion, ["class" => "form-control", "id" => "txt_graduado"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
        <div class="form-group">
            <label for="txt_fecha_grado" class="col-sm-5 control-label"><?= Yii::t("formulario", "Fecha de Graduación") ?></label>
            <div class="col-sm-7">
                <?=
                DatePicker::widget([
                    'name' => 'txt_fecha_grado',
                    'value' => '',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => ["class" => "form-control", "id" => "txt_fecha_grado", "placeholder" => Yii::t("formulario", "Fecha de Graduación")],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => Yii::$app->params["dateByDatePicker"],
                    ]]
                );
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
        <div class="form-group">
            <label for="txt_año_est" class="col-sm-5 control-label"><?= Yii::t("formulario", "Último año de estudio") ?></label>
            <div class="col-sm-7">
                <input type="text" class="form-control" data-required="false" value="" id="txt_año_est" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Último año de estudio") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">   
    <div class="form-group">
        <label for="txt_neces_aprender" class="col-sm-5 control-label"><?= Yii::t("formulario", "Necesidades de aprendizajes") ?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control" value="" id="txt_neces_aprender" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Necesidades de aprendizajes") ?>">
        </div>
    </div>
</div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
    <div class="col-md-2">
        <a id="paso2back" href="javascript:" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-menu-left"></span><?= Yii::t("formulario", "Back") ?> </a>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8"> &nbsp;</div>
    <div class="col-md-2">
        <a id="paso2next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
    </div>
</div>