<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Formación Profesional") ?></span></h3><br></br>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_titulo_3erNivel" class="col-sm-3 control-label"><?= Yii::t("formulario", "Título de Tercer Nivel") ?> <span class="text-danger">*</span> </label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_titulo_3erNivel" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Título de Tercer Nivel") ?>">
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_universidad1" class="col-sm-3 control-label"><?= Yii::t("formulario", "Universidad") ?> <span class="text-danger">*</span> </label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_universidad1" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nombre de la Universidad") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_año_grado1" class="col-sm-3 control-label"><?= Yii::t("formulario", "Año de Graduación") ?> <span class="text-danger">*</span> </label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_año_grado1" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Año de Graduación") ?>">
            </div>
        </div>
    </div><br><br></br>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_titulo_4toNivel" class="col-sm-3 control-label"><?= Yii::t("formulario", "Título de Cuarto Nivel") ?></label>
            <div class="col-sm-7">
                <input type="text" class="form-control keyupmce" id="txt_titulo_4toNivel" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Título de Cuarto Nivel") ?>">
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_universidad2" class="col-sm-3 control-label"><?= Yii::t("formulario", "Universidad") ?></label>
            <div class="col-sm-7">
                <input type="text" class="form-control keyupmce" id="txt_universidad2" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nombre de la Universidad") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_año_grado2" class="col-sm-3 control-label"><?= Yii::t("formulario", "Año de Graduación") ?></label>
            <div class="col-sm-7">
                <input type="text" class="form-control keyupmce" id="txt_año_grado2" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Año de Graduación") ?>">
            </div>
        </div>
    </div><br><br></br>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Datos Laborales") ?></span></h3><br></br>
</div>
<!-- empieza datos laborales-->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <label for="txt_laborable" class="col-sm-5 control-label"><?= Yii::t("formulario", "¿Se encuentra laborando actualmente?") ?><span class="text-danger">*</span></label>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <label>
                <input type="radio" name="laborals-si" id="laborals-si" value="1" checked> Si<br>
            </label>
            <label>
                <input type="radio" name="laborals-no" id="laborals-no" value="2" > No<br>
            </label>
        </div>
    </div>
</div>
<div style="display: block;" id="Divlaboral">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_empresa" class="col-sm-3 control-label"><?= Yii::t("formulario", "Empresa, Centro o Institución") ?><span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_empresa" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nombre de la empresa a la que trabaja") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_cargo" class="col-sm-3 control-label"><?= Yii::t("formulario", "Cargo que desempeña") ?><span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_cargo" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Cargo que desempeña") ?>">
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_telefono_emp" class="col-sm-3 control-label"><?= Yii::t("formulario", "Phone") ?><span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation" data-required="false" value="" id="txt_telefono_emp" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Télefono de la empresa") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_pais_emp" class="col-sm-3 control-label"><?= Yii::t("formulario", "País") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_pais_emp", 0, $arr_pais_emp, ["class" => "form-control", "id" => "cmb_pais_emp"]) ?>
                </div>
            </div>
        </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="cmb_prov_emp" class="col-sm-3 control-label"><?= Yii::t("formulario", "State") ?> <span class="text-danger">*</span> </label>
            <div class="col-sm-7">
                <?= Html::dropDownList("cmb_prov_emp", 0, $arr_prov_emp, ["class" => "form-control", "id" => "cmb_prov_emp"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="cmb_ciu_emp" class="col-sm-3 control-label"><?= Yii::t("formulario", "Cantón") ?> <span class="text-danger">*</span> </label>
            <div class="col-sm-7">
                <?= Html::dropDownList("cmb_ciu_emp", 0, $arr_ciu_emp, ["class" => "form-control", "id" => "cmb_ciu_emp"]) ?>
            </div>
        </div>
    </div>
    </div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_parroquia" class="col-sm-3 control-label"><?= Yii::t("formulario", "Parroquia") ?><span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_parroquia" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Parroquia") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_direc_emp" class="col-sm-3 control-label"><?= Yii::t("formulario", "Dirección donde Labora") ?><span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation keyupmce" id="txt_direc_emp" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Dirección donde Labora") ?>">
            </div>
        </div>
    </div>
    </div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_añoingreso_emp" class="col-sm-3 control-label"><?= Yii::t("formulario", "Año de Ingreso") ?><span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation" id="txt_añoingreso_emp" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Año de Ingreso") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_correo_emp" class="col-sm-3 control-label"><?= Yii::t("formulario", "Correo Electrónico") ?><span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" class="form-control PBvalidation keyupmce" value="" id="txt_correo_emp" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="txt_cat_ocupacional" class="col-sm-3 control-label"><?= Yii::t("formulario", "Categoría Ocupacional") ?><span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <?= Html::dropDownList("txt_cat_ocupacional", 0, $arr_categoria, ["class" => "form-control", "id" => "txt_cat_ocupacional"]) ?>
            </div>
        </div>
    </div><br><br></br>
</div>
</div><!-- fin empieza datos laborales-->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Habilidades de Idiomas") ?></span></h3>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="cmb_idioma1" class="col-sm-3 control-label"><?= Yii::t("formulario", "Idioma 1") ?> <span class="text-danger">*</span> </label>
            <div class="col-lg-7">
                <?= Html::dropDownList("cmb_idioma1", 0, $arr_idioma, ["class" => "form-control", "id" => "cmb_idioma1"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="cmb_nivelidioma1" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nivel Idioma") ?> <span class="text-danger">*</span> </label>
            <div class="col-lg-7">
                <?= Html::dropDownList("cmb_nivelidioma1", 0, $arr_nivelidioma, ["class" => "form-control", "id" => "cmb_nivelidioma1"]) ?>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group">
            <label for="cmb_idioma2" class="col-sm-3 control-label"><?= Yii::t("formulario", "Idioma 2") ?> <span class="text-danger">*</span> </label>
            <div class="col-lg-7">
                <?= Html::dropDownList("cmb_idioma2", 0, $arr_idioma, ["class" => "form-control", "id" => "cmb_idioma2"]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div class="form-group" id="Dividiomas">
            <label for="cmb_nivelidioma2" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nivel Idioma") ?> <span class="text-danger">*</span> </label>
            <div class="col-lg-7">
                <?= Html::dropDownList("cmb_nivelidioma2", 0, $arr_nivelidioma, ["class" => "form-control", "id" => "cmb_nivelidioma2"]) ?>
            </div>
        </div>
    </div><br><br></br>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div style="display: none;" id="Divotroidioma">
            <label for="txt_nombreidioma" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nombre del Idioma") ?> <span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" maxlength="10" class="form-control PBvalidation keyupmce" id="txt_nombreidioma" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Nombre del Idioma") ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <div style="display: none;" id="Divotronivelidioma">
            <label for="cmb_nivelotroidioma" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nivel Idioma") ?> <span class="text-danger">*</span> </label>
            <div class="col-lg-7">
                <?= Html::dropDownList("cmb_nivelotroidioma", 0, $arr_nivelidioma, ["class" => "form-control", "id" => "cmb_nivelotroidioma"]) ?>
            </div>
        </div>
    </div><br><br></br>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Otra Información") ?></span></h3>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <label for="txt_discapacidad" class="col-sm-5 control-label"><?= Yii::t("bienestar", "¿Do you have any type of disability?") ?><span class="text-danger">*</span></label>
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
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div id="discapacidad" style="display: block;">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_tipo_discap" class="col-sm-3 control-label"><?= Yii::t("formulario", "Tipo de Discapacidad") ?> <span class="text-danger">*</span> </label>
                <div class="col-lg-7">
                    <?= Html::dropDownList("cmb_tipo_discap", 0, $arr_tip_discap, ["class" => "form-control", "id" => "cmb_tipo_discap"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_porc_discapacidad" class="col-sm-3 control-label keyupmce"><?= Yii::t("bienestar", "Percentage Disability") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_porc_discapacidad" data-type="number" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Percentage Disability") ?>">
                </div>
            </div>
        </div>
    </div><br><br></br>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <label for="txt_docencia" class="col-sm-5 control-label"><?= Yii::t("bienestar", "Docencia") ?><span class="text-danger">*</span></label>
        <div class="col-sm-7">
            <label>
                <input type="radio" name="signup-doc" id="signup-doc" value="1" checked> Si<br>
            </label>
            <label>
                <input type="radio" name="signup-doc_no" id="signup-doc_no" value="2" > No<br>
            </label>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div id="docencia" style="display: block;">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_año_docencia" class="col-sm-3 control-label keyupmce"><?= Yii::t("bienestar", "Años de Docencia") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_año_docencia" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Años de docencia") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_area_docencia" class="col-sm-3 control-label keyupmce"><?= Yii::t("bienestar", "Área de Docencia") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_area_docencia" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Área de Docencia") ?>">
                </div>
            </div>
        </div>
    </div><br><br></br>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-group">
        <label for="txt_investigacion" class="col-sm-5 control-label"><?= Yii::t("bienestar", "Investigación") ?><span class="text-danger">*</span></label>
        <div class="col-sm-7">
            <label>
                <input type="radio" name="signup-inv" id="signup-inv" value="1" checked> Si<br>
            </label>
            <label>
                <input type="radio" name="signup-inv_no" id="signup-inv_no" value="2" > No<br>
            </label>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div id="investigacion" style="display: block;">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_articulos" class="col-sm-3 control-label keyupmce"><?= Yii::t("bienestar", "Número de Árticulos Publicados") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_articulos" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Número de Árticulos Publicados") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_area_investigacion" class="col-sm-3 control-label keyupmce"><?= Yii::t("bienestar", "Área de Investigación") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_area_investigacion" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("bienestar", "Área de Investigación") ?>">
                </div>
            </div>
        </div>
    </div><br><br></br>
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