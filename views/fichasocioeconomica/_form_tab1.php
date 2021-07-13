<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;

?>
<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Personal") ?></span></h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_primer_nombre" class="col-sm-5 control-label"> <?= Yii::t("formulario", "First Name") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_primer_nombre" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "First Name") ?>"> 
                </div>        
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_segundo_nombre" class="col-sm-5 control-label"><?= Yii::t("formulario", "Middle Name") ?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control keyupmce" id="txt_segundo_nombre" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Middle Name") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_primer_apellido" class="col-sm-5 control-label"><?= Yii::t("formulario", "Last Name") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_primer_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_segundo_apellido" class="col-sm-5 control-label"><?= Yii::t("formulario", "Last Second Name") ?> </label>
                <div class="col-sm-7">
                    <input type="text" class="form-control keyupmce" id="txt_segundo_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Second Name") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">    
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_celular" class="col-sm-5 control-label"><?= Yii::t("formulario", "CellPhone") ?> <span class="text-danger">*</span> </label>        
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation" value="" data-required="false" id="txt_celular" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_genero" class="col-sm-5 control-label"><?= Yii::t("formulario", "Gender") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_genero", $per_genero, $genero, ["class" => "form-control", "id" => "cmb_genero"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_nacionalidad" class="col-sm-5 control-label"><?= Yii::t("formulario", "Nationality") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                <?= Html::dropDownList("cmb_nacionalidad", '', $arr_nacionalidad, ["class" => "form-control", "id" => "cmb_nacionalidad"]) ?>
            </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_pais" class="col-sm-5 control-label"><?= Yii::t("formulario", "Pais de Origen") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_pais", 0, $arr_pais, ["class" => "form-control", "id" => "cmb_pais"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_pais_reside" class="col-sm-5 control-label"><?= Yii::t("formulario", "Pais de Residencia") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_pais_reside", 0, $arr_pais_reside, ["class" => "form-control", "id" => "cmb_pais_reside"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_etnia" class="col-sm-5 control-label"><?= Yii::t("formulario", "Ethnic") ?> </label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_etnia", 0, $arr_etnia, ["class" => "form-control", "id" => "cmb_etnia"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="text_actitudes" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Actitudes Destacadas") ?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="text_actitudes" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Actitudes Destacadas") ?>">
                </div>
            </div>
        </div><br><br></br><br></br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="text_formacion_madre" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Nivel de Formación de la Madre") ?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="text_formacion_madre" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel de Formación de la Madre") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="text_formacion_padre" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Nivel de Formación del Padre") ?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="text_formacion_padre" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nivel de Formación del Padre") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="text_miembros_hogar" class="col-sm-5 control-label keyupmce"><?= Yii::t("formulario", "Cantidad de Miembros del Hogar") ?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="text_miembros_hogar" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Cuantas personas viven el hogar") ?>">
                </div>
            </div>
        </div><br><br></br><br></br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Additional") ?></span></h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
        <div class="form-group">
            <label for="txt_discapacidad" class="col-sm-5 control-label"><?= Yii::t("bienestar", "¿Do you have any type of disability?") ?></label>
            <div class="col-sm-7">
                <label>                
                    <input type="radio" name="signup-dis" id="signup-dis" value="1" checked> Si<br>                   
                </label>
                <label>            
                    <input type="radio" name="signup-dis_no" id="signup-dis_no" value="2" > No<br>
                </label>
            </div> 
        </div>
    </div>  
    <div id="discapacidad" style="display: block;" >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
            <div class="form-group">
                <label for="cmb_tip_discap" class="col-sm-5 control-label keyupmce"><?= Yii::t("bienestar", "Type Disability") ?></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_tip_discap", 0, $arr_tip_discap, ["class" => "form-control", "id" => "cmb_tip_discap"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
            <div class="form-group">
                <label for="txt_porc_discapacidad" class="col-sm-5 control-label keyupmce"><?= Yii::t("bienestar", "Percentage Disability") ?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_porc_discapacidad" data-type="number" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Percentage Disability") ?>">
                </div>
            </div>
        </div><br><br></br><br></br>
    </div>    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">   
        <div class="col-md-10"></div>
        <div class="col-md-2">
            <a id="paso1next" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Next") ?> <span class="glyphicon glyphicon-menu-right"></span></a>
        </div>
    </div>
</form>