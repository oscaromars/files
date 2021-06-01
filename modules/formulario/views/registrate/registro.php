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
use app\modules\formulario\Module as formulario;
use app\modules\academico\Module as academico;
formulario::registerTranslations();
academico::registerTranslations();
?>
<form class="form-horizontal">  
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Personal") ?></span></h3>
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
                <label for="txt_institucion" class="col-sm-5 control-label"><?= Yii::t("formulario", "Institution") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_institucion"  data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Institution") ?>">
                </div>
            </div>
        </div> 
    </div>          
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= formulario::t("ficha", "Academic Offer") ?></span></h3>
    </div>
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_unidad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_unidad"><?= Yii::t("formulario", "Academic unit") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">                                      
                    <?= Html::dropDownList("cmb_unidad", 0, array_merge([Yii::t("formulario", "Select")], $arr_unidad), ["class" => "form-control", "id" => "cmb_unidad"]) ?>                                       
                </div>
            </div>
        </div>  
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_carrera_programa" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_unidad"><?= academico::t("Academico", "Career/Program") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">                                      
                    <?= Html::dropDownList("cmb_carrera_programa", 0, array_merge([Yii::t("formulario", "Select")], $arr_carrera_prog), ["class" => "form-control", "id" => "cmb_carrera_programa"]) ?>                                       
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label for="rb_estudios_ant" class="col-sm-5 control-label"><?= formulario::t("ficha", "Have you done previous studies?") ?><span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <label>
                    <input type="radio" name="signup-si" id="signup-si" value="1" >Si<br>
                </label>
                <label>
                    <input type="radio" name="signup-no" id="signup-no" value="0" checked>No<br>
                </label>            
            </div>
        </div>
    </div>
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="otro_estudio"  style="display: none;">        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h5><b><span id="lbl_Personeria"><?= formulario::t("ficha", "If your answer is (YES), indicate the name of the institution where you completed your studies and the degree you completed.") ?></span></b></h5>
        </div>        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">        
            <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="lbl_institucion_acad" class="col-sm-5 col-md-5 col-xs-5 col-lg-5 control-label" id="lbl_institucion_acad"><?= formulario::t("ficha", "Academic institution") ?></label>
                    <div class="col-sm-7 col-md-7 col-xs-7 col-lg-7">                                      
                        <?= Html::dropDownList("cmb_institucion", 0, array_merge([Yii::t("formulario", "Select")], $arr_institucion), ["class" => "form-control", "id" => "cmb_institucion"]) ?>                                       
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                <div class="form-group">
                    <label for="txt_carrera" class="col-sm-5 control-label"><?= academico::t("Academico", "Career") ?><span class="text-danger"></span></label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" value="" id="txt_carrera"  data-type="alfa" data-keydown="true" placeholder="<?= academico::t("Academico", "Career")?>">
                    </div>
                </div>
            </div>   
        </div>    
    </div>
</form>