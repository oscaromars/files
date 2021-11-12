<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\CFileInputAjax;
use app\widgets\PbSearchBox\PbSearchBox;
use app\modules\academico\Module as academico;
use yii\web\Session;
use kartik\select2\Select2;
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

academico::registerTranslations();

?>
<?= Html::hiddenInput('txth_ids', base64_decode($_GET['ids']), ['id' => 'txth_ids']); ?>

<form class="form-horizontal">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_Personeria"><?= Yii::t("formulario", "Formulario de Inscripción Grado") ?></span></h3>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <p class="text-danger"> <?= Yii::t("formulario", "Fields with * are required") ?> </p>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_unidad" class="col-sm-3 control-label"><?= Yii::t("crm", "Academic Unit"); ?></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_unidad", 1, $arr_unidad, ["class" => "form-control", "id" => "cmb_unidad", "Disabled" => "true"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_carrera" class="col-sm-3 control-label"><?= Yii::t("crm", "Carrera"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_carrera", 0, $arr_carrera, ["class" => "form-control PBvalidation", "id" => "cmb_carrera"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_modalidad" class="col-sm-3 control-label"><?= Yii::t("formulario", "Mode"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_modalidad", 0, $arr_modalidad, ["class" => "form-control PBvalidation", "id" => "cmb_modalidad"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="lbl_periodo" class="col-sm-3 control-label"><?= Yii::t("formulario", "Period"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_periodo", 0, $arr_periodo, ["class" => "form-control PBvalidation", "id" => "cmb_periodo"]) ?>
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
            <div class="form-group" id="Divcedula">
                <label for="txt_cedula" class="col-sm-3 control-label"><?= Yii::t("formulario", "Cédula/Pasaporte") ?> <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" maxlength="10" class="form-control PBvalidation keyupmce" id="txt_cedula" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "National identity document") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group" style="display: none;" id="Divpasaporte">
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
                <label for="txt_primer_nombre" class="col-sm-3 control-label"><?= Yii::t("formulario", "First Name") ?><span class="text-danger">*</span></label>
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
                <label for="txt_primer_apellido" class="col-sm-3 control-label"><?= Yii::t("formulario", "Last Name") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_primer_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Name") ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_segundo_apellido" class="col-sm-3 control-label"><?= Yii::t("formulario", "Last Second Name") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_segundo_apellido" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Last Second Name") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_ciu_nac" class="col-sm-3 control-label"><?= Yii::t("formulario", "City of birth") ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <!-- <= Html::dropDownList("cmb_ciu_nac", $can_id_nacimiento, $arr_ciudad_nac, ["class" => "form-control can_combo", "id" => "cmb_ciu_nac"]) ?>-->
                    <?php
                        echo Select2::widget([
                        'name' => 'cmb_ciu_nac',
                        'id' => 'cmb_ciu_nac',
                        'value' => '0', // initial value
                        'data' => $arr_ciudad_nac,
                        'options' => ['placeholder' => 'Seleccionar'],
                        'pluginOptions' => [
                            'tags' => true,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 50
                        ],
                    ]); ?>
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
                <label for="cmb_nacionalidad" class="col-sm-3 control-label"><?= Yii::t("formulario", "Nacionalidad") ?><span class="text-danger">*</span></label>
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
        </div><br><br></br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3><span id="lbl_datos2"><?= Yii::t("formulario", "Datos de Contacto") ?></span></h3>
    </div><br><br></br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_pais" class="col-sm-3 control-label"><?= Yii::t("formulario", "Pais") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_pais", '', $arr_pais, ["class" => "form-control", "id" => "cmb_pais"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_provincia" class="col-sm-3 control-label"><?= Yii::t("formulario", "Provincia / Estado") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_provincia", 0, $arr_provincia, ["class" => "form-control", "id" => "cmb_provincia"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_ciudad" class="col-sm-3 control-label"><?= Yii::t("formulario", "Ciudad"); ?> <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_ciudad", 0, $arr_ciudad, ["class" => "form-control", "id" => "cmb_ciudad"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_parroquia" class="col-sm-3 control-label"><?= Yii::t("formulario", "Parroquia") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_parroquia" data-type="alfanumerico" data-keydown="true" placeholder="<?= Yii::t("formulario", "Parroquia") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_domicilio" class="col-sm-3 control-label"><?= Yii::t("formulario", "Dirección Domiciliaria") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <!-- <input type="text" class="form-control PBvalidation keyupmce" id="txt_domicilio" data-type="alfanumerico" data-keydown="true" placeholder="<= Yii::t("formulario", "Detallar la dirección de su domicilio") ?>">-->
                    <textarea  class="form-control PBvalidation keyupmce" id="txt_domicilio" data-type="alfanumerico" data-keydown="true" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_celular" class="col-sm-3 control-label"><?= Yii::t("formulario", "CellPhone")?><span class="text-danger">*</span></label>
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
        <h3><span id="lbl_datos3"><?= Yii::t("formulario", "Datos en Caso de Emergencia") ?></span></h3>
    </div><br><br></br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br></br>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_direccion_trabajo" class="col-sm-3 control-label"><?= Yii::t("formulario", "Dirección de Trabajo") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <!-- <input type="text" class="form-control PBvalidation keyupmce" id="txt_direccion_trabajo" data-type="alfa" data-keydown="true" placeholder="<= Yii::t("formulario", "Donde trabaja actualmente") ?>">-->
                    <textarea  class="form-control PBvalidation keyupmce" id="txt_direccion_trabajo" data-type="alfanumerico" data-keydown="true" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_contacto_emergencia" class="col-sm-3 control-label"><?= Yii::t("formulario", "En Caso de Emergencia") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation keyupmce" id="txt_contacto_emergencia" data-type="alfa" data-keydown="true" placeholder="<?= Yii::t("formulario", "Persona por contactar en caso de Emergencia") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_parentesco" class="col-sm-3 control-label"><?= Yii::t("formulario", "Tipo de Parentesco") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_parentesco", $tpar_id, $arr_tipparentesco, ["class" => "form-control", "id" => "cmb_parentesco"]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_telefono_emergencia" class="col-sm-3 control-label"><?= Yii::t("formulario", "CellPhone")?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control PBvalidation" id="txt_telefono_emergencia" data-type="number" data-keydown="true" placeholder="<?= Yii::t("formulario", "Celular de la persona de contacto en caso de emergencia ") ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="txt_direccion_persona_contacto" class="col-sm-3 control-label"><?= Yii::t("formulario", "Dirección de Persona en Caso de Emergencia") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <!-- <input type="text" class="form-control PBvalidation keyupmce" id="txt_direccion_persona_contacto" data-type="alfa" data-keydown="true" placeholder="<= Yii::t("formulario", "Dirección de la Persona de Contacto en Caso de Emergencia") ?>">-->
                    <textarea  class="form-control PBvalidation keyupmce" id="txt_direccion_persona_contacto" data-type="alfanumerico" data-keydown="true" rows="3"></textarea>
                </div>
            </div>
        </div><br><br></br>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="divmetodocan" style="display: none">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="form-group">
                <label for="cmb_metodo_solicitud" class="col-sm-3 control-label keyupmce"><?= Yii::t("formulario", "Income Method") ?><span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <?= Html::dropDownList("cmb_metodo_solicitud", 0, array_merge([Yii::t("formulario", "Select")], $arr_metodos), ["class" => "form-control", "id" => "cmb_metodo_solicitud"]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-10"></div>
        <div class="col-md-2"  style="display: none;" id="Divboton">
            <a id="paso1next" href="javascript:" class="btn btn-primary btn-block"><?php echo "Siguiente"; ?> </a>
        </div>
    </div>
</form>