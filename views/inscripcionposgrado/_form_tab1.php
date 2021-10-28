<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\web\Session;

session_start();
if (!empty($per_cedula)) {
    $tipodoc = "CED";
} else {
    if (!empty($per_pasaporte)) {
        $tipodoc = "PASS";
    }
    else{
        $tipodoc = "CED";
    }
}


?>
<?= Html::hiddenInput('txth_ids', base64_decode($_GET['ids']), ['id' => 'txth_ids']); ?>

<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Formulario de Inscripción Posgrado") ?></span></h3>
    </div><br></br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div><br></br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_unidadpos" class="col-sm-3 control-label"><?= Yii::t("formulario", "Academic unit") ?></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_unidadpos", 2,  $arr_unidad , ["class" => "form-control", "id" => "cmb_unidadpos", "disabled" => "true"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_programa" class="col-sm-3 control-label"><?= Yii::t("formulario", "Program") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_programa", 0,  $arr_programa , ["class" => "form-control", "id" => "cmb_programa"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_modalidadpos" class="col-sm-3 control-label"><?= Yii::t("formulario", "Mode") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_modalidadpos", 0,  $arr_modalidad , ["class" => "form-control", "id" => "cmb_modalidadpos"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_año" class="col-sm-3 control-label"><?= Yii::t("formulario", "Año") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_año" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Año") ?>">
                </div>
            </div>
        </div><br><br></br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Personal") ?></span></h3>
    </div><br><br></br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_tipo_dni" class="col-sm-3 control-label"><?= Yii::t("formulario", "Tipo de Identificación") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_tipo_dni", $tipodoc, $tipos_dni, ["class" => "form-control", "id" => "cmb_tipo_dni"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div id="Divcedula">
                <label for="txt_cedula" class="col-sm-3 control-label"><?= Yii::t("formulario", "Cédula/Pasaporte") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" maxlength="10" class="form-control PBvalidation keyupmce" id="txt_cedula" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "National identity document") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div style="display: none;" id="Divpasaporte">
                <label for="txt_pasaporte" class="col-sm-3 control-label"><?= Yii::t("formulario", "Cédula/Pasaporte") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" maxlength="15" class="form-control keyupmce" id="txt_pasaporte" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Passport") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_primer_nombre" class="col-sm-3 control-label"> <?= Yii::t("formulario", "First Name") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_primer_nombre" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "First Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_segundo_nombre" class="col-sm-3 control-label"><?= Yii::t("formulario", "Middle Name") ?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control keyupmce" id="txt_segundo_nombre" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Middle Name") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_primer_apellido" class="col-sm-3 control-label"><?= Yii::t("formulario", "Last Name") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_primer_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_segundo_apellido" class="col-sm-3 control-label"><?= Yii::t("formulario", "Last Second Name") ?> </label>
                <div class="col-sm-7">
                    <input type="text" class="form-control keyupmce" id="txt_segundo_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Second Name") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_ciu_nac" class="col-sm-3 control-label"><?= Yii::t("formulario", "Lugar de Nacimiento") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_ciu_nac", $can_id_nacimiento, $arr_ciudad_nac, ["class" => "form-control can_combo", "id" => "cmb_ciu_nac"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_fecha_nac" class="col-sm-3 control-label"><?= Yii::t("formulario", "Birth Date") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?=
                    DatePicker::widget([
                        'name' => 'txt_fecha_nac',
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => ["class" => "form-control PBvalidation keyupmce", "id" => "txt_fecha_nac", "data-type" => "fecha", "data-keydown" => "true", "placeholder" => Yii::t("formulario", "Birth Date yyyy-mm-dd")],
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
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_nacionalidad" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nationality") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                <?= Html::dropDownList("cmb_nacionalidad", '', $arr_nacionalidad, ["class" => "form-control", "id" => "cmb_nacionalidad"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_estado_civil" class="col-sm-3 control-label"><?= Yii::t("formulario", "Estado Civil"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_estado_civil", $eciv_id, $arr_estado_civil, ["class" => "form-control", "id" => "cmb_estado_civil"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_provincia" class="col-sm-3 control-label"><?= Yii::t("formulario", "Provincia / Estado") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_provincia", 0, $arr_provincia, ["class" => "form-control", "id" => "cmb_provincia"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_ciudad" class="col-sm-3 control-label"><?= Yii::t("formulario", "Cantón"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_ciudad", 0, $arr_ciudad, ["class" => "form-control", "id" => "cmb_ciudad"]) ?>
                </div>
            </div>
        </div><br><br></br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Data Contact") ?></span></h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_domicilio" class="col-sm-3 control-label"><?= Yii::t("formulario", "Dirección Domiciliaria") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_domicilio" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Detallar la dirección de su domicilio") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_celular" class="col-sm-3 control-label"><?= Yii::t("formulario", "CellPhone") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation" id="txt_celular" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "CellPhone") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_telefono" class="col-sm-3 control-label"><?= Yii::t("formulario", "Phone") ?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation" data-required="false" id="txt_telefono" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Phone") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_correo" class="col-sm-3 control-label"><?= Yii::t("formulario", "Email") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_correo" data-type="email" data-keydown="true" placeholder="<?= Yii::t("formulario", "Email") ?>">
                </div>
            </div>
        </div><br><br></br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Datos en Caso de Emergencia") ?></span></h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_contacto_emergencia" class="col-sm-3 control-label"><?= Yii::t("formulario", "En Caso de Emergencia") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_contacto_emergencia" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Persona por contactar en caso de Emergencia") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_parentesco" class="col-sm-3 control-label"><?= Yii::t("formulario", "Tipo de Parentesco") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_parentesco", $tpar_id, $arr_tipparentesco, ["class" => "form-control", "id" => "cmb_parentesco"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_telefono_emergencia" class="col-sm-3 control-label"><?= Yii::t("formulario", "Phone")?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation" id="txt_telefono_emergencia" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Teléfono de la persona de contacto en caso de emergencia ") ?>">
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