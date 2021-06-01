<?php
/*
 * Authors:
 * Grace Viteri <analistadesarrollo01@uteg.edu.ec> 
 * Kleber Loayza <analistadesarrollo03@uteg.edu.ec> /
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\modules\piensaecuador\Module as piensaecuador;
piensaecuador::registerTranslations();
?>
<form class="form-horizontal">  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Personal") ?></span></h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6">
            <div class="form-group">
                <label for="cmb_tipo_dni" class="col-sm-5 control-label"><?= Yii::t("formulario", "DNI 1") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_tipo_dni", 0, $tipos_dni, ["class" => "form-control", "id" => "cmb_tipo_dni"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" id="Divcedula">
            <div class="form-group">
                <label for="txt_cedula" class="col-sm-5 control-label"><?= Yii::t("formulario", "Number") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" maxlength="10" class="form-control PBvalidation keyupmce" id="txt_cedula" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "National identity document") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" style="display: none;" id="Divpasaporte">
            <div class="form-group">
                <label for="txt_pasaporte" class="col-sm-5 control-label"><?= Yii::t("formulario", "Number") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" maxlength="15" class="form-control keyupmce" id="txt_pasaporte" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Passport") ?>">
                </div>
            </div>
        </div>                      
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_nombre" class="col-sm-5 control-label"><?= Yii::t("formulario", "Names") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_nombre"  data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Names") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_apellido" class="col-sm-5 control-label"><?= Yii::t("formulario", "Last Names") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Names") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">    
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_correo" class="col-sm-5 control-label"><?= Yii::t("formulario", "Email") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation" value="" id="txt_correo" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_celular" class="col-sm-5 control-label"><?= Yii::t("formulario", "CellPhone") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation" value="" id="txt_celular" data-type="celular_sin" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                </div>
            </div>
        </div>        
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_telefono" class="col-sm-5 control-label"><?= Yii::t("formulario", "Phone") ?></label>
                <div class="col-sm-7">                        
                    <input type="text" class="form-control" value="" id="txt_telefono" data-type="telefono_sin" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_genero" class="col-sm-5 control-label"><?= Yii::t("formulario", "Gender") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_genero", 0, $arr_genero, ["class" => "form-control", "id" => "cmb_genero"]) ?>
                </div>
            </div>
        </div>             
    </div>        
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_fecha_nacimiento" class="col-sm-5 control-label"><?= Yii::t("formulario", "Birth Date") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_nacimiento',
                        'value' => $per_fecha_nacimiento,
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_nacimiento", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Birth Date yyyy-mm-dd")],
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
                <label for="cmb_nivel_estudio" class="col-sm-5 control-label"><?= Yii::t("formulario", "Instructional Level") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_nivel_estudio", 0, $arr_nivel, ["class" => "form-control", "id" => "cmb_nivel_estudio"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_provincia" class="col-sm-5 control-label"><?= Yii::t("formulario", "State") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_provincia", 0, $arr_provincia, ["class" => "form-control pro_combo", "id" => "cmb_provincia"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_ciudad" class="col-sm-5 control-label"><?= Yii::t("formulario", "City") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_ciudad", 0, $arr_ciudad, ["class" => "form-control can_combo", "id" => "cmb_ciudad"]) ?>
                </div>
            </div>
        </div>        
    </div>  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6" style="display: none">
            <div class="form-group" >
                <label for="cmb_evento" class="col-sm-5 control-label"><?= Yii::t("formulario", "Evento") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_evento", 0, $arr_evento, ["class" => "form-control", "id" => "cmb_evento"]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_ocupacion" class="col-sm-5 control-label"><?= piensaecuador::t("interes", "Occupation") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_ocupacion", 0, $arr_ocupaciones, ["class" => "form-control ocu_combo", "id" => "cmb_ocupacion"]) ?>
                </div>
            </div>
        </div>  
    </div> 
</form>